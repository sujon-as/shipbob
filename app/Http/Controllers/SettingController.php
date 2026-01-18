<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\LoginPageContent;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function settings()
    {
        $setting = Setting::first();
        return view('admin.settings.settings',compact('setting'));
    }
    public function settingApp(Request $request)
    {
        try
        {
            $data = Setting::first();

            $defaults = [
                'trial_amount' => $data ? $data->trial_amount : null,
                'frozen_amount' => $data ? $data->frozen_amount : null,
                'no_of_trial_task' => $data ? $data->no_of_trial_task : null,
                'task_timing' => $data ? $data->task_timing : null,
                'telegram_group_link' => $data ? $data->telegram_group_link : null,
                'company_name' => $data ? $data->company_name : null,
                'img_url' => $data ? $data->company_logo : null,
                'daily_task_limit' => $data ? $data->daily_task_limit : null,
                'rtt_trial_balance' => $data ? $data->rtt_trial_balance : 0,
                'is_site_active' => $data ? $data->is_site_active : 'Active',
                'min_cash_out_amount' => $data ? $data->min_cash_out_amount : 0,
                'maintain_desc_text' => $data ? $data->maintain_desc_text : null,
                'maintain_title_text' => $data ? $data->maintain_title_text : null,
                'trail_balance_text' => $data ? $data->trail_balance_text : null,
                'reserved_amount_text' => $data ? $data->reserved_amount_text : null,

                'min_ratings' => $data ? $data->min_ratings : null,
                'order_success_mgs_1' => $data ? $data->order_success_mgs_1 : null,
                'order_success_mgs_2' => $data ? $data->order_success_mgs_2 : null,
                'order_btn_text' => $data ? $data->order_btn_text : null,
                'rating_text' => $data ? $data->rating_text : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('company_logo')) {
                $filePath = $this->storeFile($request->file('company_logo'));
                $img_url = $filePath;
            }

            if ($data) {
                Setting::where('id', $data->id)->update(
                    [
                        'trial_amount' => $request->trial_amount ?? $defaults['trial_amount'],
                        'frozen_amount' => $request->frozen_amount ?? $defaults['frozen_amount'],
                        'no_of_trial_task' => $request->no_of_trial_task ?? $defaults['no_of_trial_task'],
                        'task_timing' => $request->task_timing ?? $defaults['task_timing'],
                        'telegram_group_link' => $request->telegram_group_link ?? $defaults['telegram_group_link'],
                        'company_name' => $request->company_name ?? $defaults['company_name'],
                        'daily_task_limit' => $request->daily_task_limit ?? $defaults['daily_task_limit'],
                        'rtt_trial_balance' => $request->rtt_trial_balance ?? $defaults['rtt_trial_balance'],
                        'is_site_active' => $request->is_site_active ?? $defaults['is_site_active'],
                        'min_cash_out_amount' => $request->min_cash_out_amount ?? $defaults['min_cash_out_amount'],
                        'maintain_desc_text' => $request->maintain_desc_text ?? $defaults['maintain_desc_text'],
                        'maintain_title_text' => $request->maintain_title_text ?? $defaults['maintain_title_text'],
                        'trail_balance_text' => $request->trail_balance_text ?? $defaults['trail_balance_text'],
                        'reserved_amount_text' => $request->reserved_amount_text ?? $defaults['reserved_amount_text'],
                        'company_logo' => $request->hasFile('company_logo') ? $img_url : $defaults['img_url'],

                        'min_ratings' => $request->min_ratings ?? $defaults['min_ratings'],
                        'order_success_mgs_1' => $request->order_success_mgs_1 ?? $defaults['order_success_mgs_1'],
                        'order_success_mgs_2' => $request->order_success_mgs_2 ?? $defaults['order_success_mgs_2'],
                        'order_btn_text' => $request->order_btn_text ?? $defaults['order_btn_text'],
                        'rating_text' => $request->rating_text ?? $defaults['rating_text'],
                    ]
                );
            } else {
                Setting::create(
                    [
                        'trial_amount' => $request->trial_amount ?? $defaults['trial_amount'],
                        'frozen_amount' => $request->frozen_amount ?? $defaults['frozen_amount'],
                        'no_of_trial_task' => $request->no_of_trial_task ?? $defaults['no_of_trial_task'],
                        'task_timing' => $request->task_timing ?? $defaults['task_timing'],
                        'telegram_group_link' => $request->telegram_group_link ?? $defaults['telegram_group_link'],
                        'company_name' => $request->company_name ?? $defaults['company_name'],
                        'daily_task_limit' => $request->daily_task_limit ?? $defaults['daily_task_limit'],
                        'rtt_trial_balance' => $request->rtt_trial_balance ?? $defaults['rtt_trial_balance'],
                        'is_site_active' => $request->is_site_active ?? $defaults['is_site_active'],
                        'min_cash_out_amount' => $request->min_cash_out_amount ?? $defaults['min_cash_out_amount'],
                        'maintain_desc_text' => $request->maintain_desc_text ?? $defaults['maintain_desc_text'],
                        'maintain_title_text' => $request->maintain_title_text ?? $defaults['maintain_title_text'],
                        'trail_balance_text' => $request->trail_balance_text ?? $defaults['trail_balance_text'],
                        'reserved_amount_text' => $request->reserved_amount_text ?? $defaults['reserved_amount_text'],
                        'company_logo' => $request->hasFile('company_logo') ? $img_url : $defaults['img_url'],

                        'min_ratings' => $request->min_ratings ?? $defaults['min_ratings'],
                        'order_success_mgs_1' => $request->order_success_mgs_1 ?? $defaults['order_success_mgs_1'],
                        'order_success_mgs_2' => $request->order_success_mgs_2 ?? $defaults['order_success_mgs_2'],
                        'order_btn_text' => $request->order_btn_text ?? $defaults['order_btn_text'],
                        'rating_text' => $request->rating_text ?? $defaults['rating_text'],
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

    public function aboutUs()
    {
        $aboutUs = AboutUs::first();
        return view('admin.settings.about_us',compact('aboutUs'));
    }
    public function storeAboutUs(Request $request)
    {
        try
        {
            $data = AboutUs::first();

            $defaults = [
                'user_agreement' => $data ? $data->user_agreement : null,
                'privacy' => $data ? $data->privacy : null,
            ];

            if ($data) {
                AboutUs::where('id', $data->id)->update(
                    [
                        'user_agreement' => trim($request->user_agreement) ?? $defaults['user_agreement'],
                        'privacy' => trim($request->privacy) ?? $defaults['privacy'],
                    ]
                );
            } else {
                AboutUs::create(
                    [
                        'user_agreement' => trim($request->user_agreement) ?? $defaults['user_agreement'],
                        'privacy' => trim($request->privacy) ?? $defaults['privacy'],
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
            Log::error('Error in updating about us: ', [
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
    public function loginPageContent()
    {
        $loginPageContent = LoginPageContent::first();
        return view('admin.settings.loginPageContents',compact('loginPageContent'));
    }
    public function updateLoginPageContent(Request $request)
    {
        try
        {
            $data = LoginPageContent::first();

            $defaults = [
                'name' => $data ? $data->name : null,
                'title' => $data ? $data->title : null,
                'description' => $data ? $data->description : null,
                'img' => $data ? $data->img : null,
            ];

            // Handle file upload
            $img_url = '';
            if ($request->hasFile('img')) {
                $filePath = $this->storeLoginFile($request->file('img'));
                $img_url = $filePath;
                $this->deleteLoginOldFile($data);
            }


            if ($data) {
                LoginPageContent::where('id', $data->id)->update(
                    [
                        'name' => $request->name ?? $defaults['name'],
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
                    ]
                );
            } else {
                LoginPageContent::create(
                    [
                        'name' => $request->name ?? $defaults['name'],
                        'title' => $request->title ?? $defaults['title'],
                        'description' => $request->description ?? $defaults['description'],
                        'img' => $request->hasFile('img') ? $img_url : $defaults['img'],
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
            Log::error('Error in updating login page content: ', [
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
    public function downloadDatabase()
    {
        // dd('downloadDatabase');
        // Database config
        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // File name
        $date = Carbon::now()->format('Y_m_d');
        $fileName = "shipbob_{$date}.sql";
        $filePath = storage_path("app/{$fileName}");

        $appEnv = config('app.env');
        // dd('$appEnv', $appEnv);
        if ($appEnv === 'local') {
            return $this->localDbDownload($host,$user,$pass,$db,$filePath);
        }

        return $this->liveDbDownload($host,$user,$pass,$db,$filePath);
    }
    private function liveDbDownload($host,$user,$pass,$db,$filePath)
    {
        // mysqldump command
        $command = "mysqldump -h{$host} -u{$user} -p{$pass} {$db} > {$filePath}";

        // Execute
        $result = exec($command);
        // dd('$result', $result);

        // Download file
        return Response::download($filePath)->deleteFileAfterSend(true);
    }
    private function localDbDownload($host,$user,$pass,$db,$filePath)
    {
        // Windows mysqldump full path
        $mysqldump = '"F:\sujon_as\xampp_8.2\mysql\bin\mysqldump.exe"';   // XAMPP
        // $mysqldump = '"C:\laragon\bin\mysql\mysql-8.0.33-winx64\bin\mysqldump.exe"'; // Laragon

        // Build command (IMPORTANT: password directly after -p)
        $command = "{$mysqldump} -h {$host} -u {$user} -p{$pass} {$db} > \"{$filePath}\"";

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($filePath) || filesize($filePath) == 0) {
//            return response()->json([
//                'error' => 'Database export failed',
//                'command' => $command
//            ], 500);
            // Log the error
            Log::error('Database export failed: ', [
                'command' => $command,
                'resultCode' => $resultCode,
                'output' => $output
            ]);

            $notification=array(
                'message' => 'Database export failed!!!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    private function storeFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function updateFile($file, $data)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/logo'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path following storeFile function
        $fileName = uniqid('logo_', true) . '.' . $file->getClientOriginalExtension();

        // Delete the old file if it exists
        $this->deleteOldFile($data);

        // Move the new file to the destination directory
        $file->move($directory, $fileName);

        // Store path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function deleteOldFile($data)
    {
        // TODO: ensure from database
        if (!empty($data->company_logo)) { # ensure from database
            $oldFilePath = public_path($data->company_logo); // Use without prepending $filePath
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFilePath]);
                return false;
            }
        }
    }
    private function storeLoginFile($file)
    {
        // Define the directory path
        // TODO: Change path if needed
        $filePath = 'uploads/login'; # change path if needed
        $directory = public_path($filePath);

        // Ensure the directory exists
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Generate a unique file name
        // TODO: Change path if needed
        $fileName = uniqid('login_', true) . '.' . $file->getClientOriginalExtension();

        // Move the file to the destination directory
        $file->move($directory, $fileName);

        // path & file name in the database
        $path = $filePath . '/' . $fileName;
        return $path;
    }
    private function deleteLoginOldFile($data)
    {
        // TODO: ensure from database
        if (!empty($data->img)) { # ensure from database
            $oldFilePath = public_path($data->img); // Use without prepending $filePath
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath); // Delete the old file
                return true;
            } else {
                Log::warning('Old file not found for deletion', ['path' => $oldFilePath]);
                return false;
            }
        }
    }
}
