<?php

namespace App\Http\Controllers;

use App\Http\Requests\LevelRequest;
use App\Models\Level;
use App\Models\Setting;
use App\Models\VipDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use DataTables;
use DB;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

               $events = Level::select('*');

                    return Datatables::of($events)
                        ->addIndexColumn()

                        ->addColumn('title', function($row){
                            return $row->title;
                        })

                        ->addColumn('description', function($row){
                            return $row->description;
                        })

                        ->addColumn('is_default', function($row){
                            return $row->is_default == 1 ? 'Yes' : 'No';
                        })

                        ->addColumn('bg_image', function($row){
                            $url = asset($row->bg_image);
                            return '<img src="' . $url . '" alt="VIP Image" style="height:60px;">';
                        })

                        ->addColumn('action', function($row){

                            $btn = "";
                            $btn .= '&nbsp;';
                            $btn .= ' <a href="'.route('details.vips.show',$row->id).'" class="btn btn-primary btn-sm">
                                        Details
                                     </a>';

                            $btn .= '&nbsp;';


                            $btn .= ' <a href="'.route('vips.show',$row->id).'" class="btn btn-primary btn-sm action-button edit-level" data-id="'.$row->id.'"><i class="fa fa-edit"></i></a>';

                            $btn .= '&nbsp;';

                            $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-level action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                            return $btn;
                        })
                        ->rawColumns(['title','description','bg_image','is_default','action'])
                        ->make(true);
            }

            return view('admin.levels.index');
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.levels.create');
    }
    public function store(LevelRequest $request)
    {
        DB::beginTransaction();
        try
        {
            // Correct way to check file
            $path = null;
            if($request->hasFile('bg_image')) {
                $filePath = $this->storeFile($request->file('bg_image'), 'uploads/levels', 'level_bg_');
                $path = $filePath ?? '';
            }

            if ($request->is_default == 1) {
                // Reset all other levels to not default
                Level::where('is_default', true)->update(['is_default' => false]);
            }

            $level = new Level();
            $level->title = $request->title;
            $level->description = $request->description;
            $level->bg_image = $path;
            $level->is_default = $request->is_default ?? false;
            $level->save();

            $notification=array(
                'message' => 'Successfully a level has been added',
                'alert-type' => 'success',
            );
            DB::commit();

            return redirect()->route('vips.index')->with($notification);

        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in storing level: ', [
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
    public function show(Level $vip)
    {
        return view('admin.levels.edit', compact('vip'));
    }
    public function edit(Level $level)
    {
        //
    }
    public function update(LevelRequest $request, Level $vip)
    {
        try
        {
            // Correct way to check file
            $path = $vip->bg_image;

            if ($request->hasFile('bg_image')) {
                $filePath = $this->updateFile($request->file('bg_image'), 'uploads/levels', 'level_bg_', $vip->bg_image);
                $path = $filePath;
            }

            if ($request->is_default == 1) {
                // Reset all other levels to not default
                Level::where('is_default', true)->update(['is_default' => false]);
            }

            $vip->title = $request->title;
            $vip->description = $request->description;
            $vip->bg_image = $path;
            $vip->is_default = $request->is_default ?? false;
            $vip->save();

            $notification=array(
                'message'=>'Successfully the level has been updated',
                'alert-type'=>'success',
            );

            return redirect()->route('vips.index')->with($notification);

        } catch(Exception $e) {
            // Log the error
            Log::error('Error in updating level: ', [
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
    public function destroy(Level $vip)
    {
        try
        {
            // 1. Delete associated files
            if (!empty($vip->bg_image)) {
                $this->deleteOldFile($vip->bg_image);
            }

            $vip->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully the level has been deleted']);
        } catch(Exception $e) {
            DB::rollback();
            // Log the error
            Log::error('Error in deleting level: ', [
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
    public function vpSettings()
    {
        $setting = Setting::first();
        return view('admin.levels.settings',compact('setting'));
    }
    public function vpSettingApp(Request $request)
    {
        try
        {
            $data = Setting::first();

            $defaults = [
                'vip_bg_image' => $data ? $data->vip_bg_image : null,
                'vip_mgs' => $data ? $data->vip_mgs : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('vip_bg_image')) {
                $filePath = $this->updateFile($request->file('vip_bg_image'), 'uploads/levels', 'vip_bg_', $defaults['vip_bg_image']);
                $img_url = $filePath;
            }

            if ($data) {
                Setting::where('id', $data->id)->update(
                    [
                        'vip_bg_image' => $request->hasFile('vip_bg_image') ? $img_url : $defaults['vip_bg_image'],
                        'vip_mgs' => $request->vip_mgs ?? $defaults['vip_mgs'],
                    ]
                );
            } else {
                Setting::create(
                    [
                        'vip_bg_image' => $request->hasFile('vip_bg_image') ? $img_url : $defaults['vip_bg_image'],
                        'vip_mgs' => $request->vip_mgs ?? $defaults['vip_mgs'],
                    ]
                );
            }

            $notification = [
                'message'    => 'Successfully updated',
                'alert-type' => 'success',
            ];

            return redirect()->back()->with($notification);

        } catch (Exception $e) {
            // Log the error
            Log::error('Error in updating settings: ', [
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
    public function detailsShow($id)
    {
        $level = Level::findOrFail($id);

        $vipDetails = VipDetail::where('vip_id', $id)->first();

        return view('admin.levels.detailsUpdate', compact('level', 'vipDetails'));
    }
    public function detailsUpdate(LevelRequest $request, $id)
    {
        // Find or create vip details row
        $vipDetails = VipDetail::firstOrNew(['vip_id' => $id]);

        $vipDetails->upgrade_text = $request->upgrade_text;
        $vipDetails->progress_in_percentage = $request->progress_in_percentage;
        $vipDetails->showing_amount_text = $request->showing_amount_text;
        $vipDetails->authority_text = $request->authority_text;
        $vipDetails->save();

        return redirect()->back()->with([
            'message' => 'VIP Details updated successfully.',
            'alert-type' => 'success'
        ]);
    }
    private function storeFile($file, $filePath, $prefix)
    {
        // Define the directory path
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        # $fileName = uniqid('flag_', true) . '.' . $file->getClientOriginalExtension();
        $fileName = uniqid($prefix, true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function updateFile($file, $filePath, $prefix, $oldFilePath = null)
    {
        // Delete the old file if it exists
        $this->deleteOldFile($oldFilePath);

        // Store path & file name in the database
        $path = $this->storeFile($file, $filePath, $prefix);
        return $path;
    }
    private function deleteOldFile($oldFilePath)
    {
        // TODO: ensure from database
        if (!empty($oldFilePath)) { # ensure from database
            $oldFullFilePath = public_path($oldFilePath); // Use without prepending $filePath
            if (file_exists($oldFullFilePath)) {
                unlink($oldFullFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFullFilePath]);
                return false;
            }
        }
    }
}
