<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrozenAmountRequest;
use App\Http\Requests\LevelAssignRequest;
use App\Http\Requests\PackageAssignRequest;
use App\Http\Requests\PackageRequest;
use App\Models\AssignLevel;
use App\Models\AssignPackage;
use App\Models\FrozenAmount;
use App\Models\Level;
use App\Models\Package;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LevelAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

               $assignLevels = AssignLevel::with('user', 'level')->select('*')->latest();

                    return Datatables::of($assignLevels)
                        ->addIndexColumn()

                        ->addColumn('name', function($row){
                            return optional($row->user)->username
                                ? "{$row->user->username} ({$row->user->uid})"
                                : 'N/A';
                        })

                        ->addColumn('level', function($row){
                            return $row->level->title;
                        })
                        ->addColumn('status', function($row){
                            $isApproved = $row->status === 'Active';
                            $checkboxClass = $isApproved ? 'active-level' : 'decline-level';
                            $checked = $isApproved ? 'checked' : '';
                            #$disabled = $isApproved ? 'disabled' : ''; // prevent toggling if approved
                            $disabled = ''; // prevent toggling if approved

                            return '<label class="switch">
                                    <input class="' . $checkboxClass . '" id="level-status-update" type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $row->id . '">
                                    <span class="slider round"></span>
                                </label>';
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';

                            $btn .= ' <a href="'.route('level-assign.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-frozenAmount" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-AssignLevel action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->filter(function ($query) use ($request) {
                            if ($request->has('search') && $request->search['value'] != '') {
                                $searchValue = $request->search['value'];
                                $query->where(function($q) use ($searchValue) {
                                    $q->where('level_id', 'like', "%{$searchValue}%")
                                        ->orWhereHas('user', function ($uq) use ($searchValue) {
                                            $uq->where('username', 'like', "%{$searchValue}%")
                                                ->orWhere('uid', 'like', "%{$searchValue}%");
                                        })
                                        ->orWhereHas('level', function ($uq) use ($searchValue) {
                                            $uq->where('title', 'like', "%{$searchValue}%");
                                        });
                                });
                            }
                        })
                        ->rawColumns(['name', 'level', 'status', 'action'])
                        ->make(true);
            }

            return view('admin.levelAssign.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        $assignedVipLevelUsers = AssignLevel::get('user_id');

        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedVipLevelUsers)
            ->latest()
            ->get();

        $levels = Level::latest()->get();
        return view('admin.levelAssign.create', compact('users', 'levels'));
    }
    public function store(LevelAssignRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $assignLevel = new AssignLevel();
            $assignLevel->user_id = $request->user_id;
            $assignLevel->level_id = $request->level_id;
            $assignLevel->status = $request->status;
            $assignLevel->save();

            $notification=array(
                'message' => 'Successfully assign vip level.',
                'alert-type' => 'success',
            );

            DB::commit();

            return redirect()->route('level-assign.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing assign Level: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function show(AssignLevel $levelAssign)
    {
        $users = User::where('role', 'user')->where('status', 'active')->get();
        $levels = Level::latest()->get();
        return view('admin.levelAssign.edit', compact('levels', 'users', 'levelAssign'));
    }
    public function edit(AssignLevel $assignLevel)
    {
        //
    }
    public function update(LevelAssignRequest $request, AssignLevel $levelAssign)
    {
        try
        {
            $levelAssign->user_id = $request->user_id;
            $levelAssign->level_id = $request->level_id;
            $levelAssign->status = $request->status;
            $levelAssign->save();

            $notification=array(
                'message'=>'Successfully the Assign VIP Level has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('level-assign.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating Assign Level: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }
    public function destroy(AssignLevel $levelAssign)
    {
        try
        {
            $levelAssign->delete();
            return response()->json([
                'status' => true,
                'message'=> 'Successfully the Assign VIP Level has been deleted'
            ]);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting Assign Level: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!!!'
            ]);
        }
    }
    public function alStatusUpdate(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $data = AssignLevel::findorfail($request->al_id);
            $data->status = $request->status;
            $data->update();

            DB::commit();
            return response()->json(['status'=>true, 'message'=>'Successfully updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
