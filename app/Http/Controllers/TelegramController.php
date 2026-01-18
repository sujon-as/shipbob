<?php

namespace App\Http\Controllers;

use App\Jobs\CheckTelegramNumber;
use App\Models\TelegramCheck;
use DataTables;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            if($request->ajax()){

                $data = TelegramCheck::where('has_telegram', 'yes')
                    ->latest()
                    ->select('*')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('phone', function($row){
                        return $row->phone ?? 'N/A';
                    })

                    ->addColumn('has_telegram', function($row){
                        return $row->has_telegram ?? 'N/A';
                    })
                    // search customization
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search') && $request->search['value'] != '') {
                            $searchValue = $request->search['value'];
                            $query->where(function($q) use ($searchValue) {
                                $q->where('phone', 'like', "%{$searchValue}%");
                            });
                        }
                    })
                    ->rawColumns(['phone','has_telegram'])
                    ->make(true);
            }

            return view('admin.telegram.index');
        } catch(Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
    public function create()
    {
        return view('admin.telegram.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phones' => 'required|string'
        ]);

        $phones = array_map('trim', explode(',', $request->phones));

        $app_id = config('services.telegram_checks.app_id');
        $api_hash = config('services.telegram_checks.api_hash');
        $session_name = config('services.telegram_checks.session_name');

        $payload = [
            'app_id' => $app_id,
            'api_hash' => $api_hash,
            'session_name' => $session_name,
        ];

        foreach ($phones as $phone) {
            CheckTelegramNumber::dispatch($phone, $payload);
        }

        $notification=array(
            'message' => 'Numbers checked successfully!',
            'alert-type' => 'success',
        );

        return redirect()->route('telegram.index')->with($notification);
    }

    private function checkSingleNumber(string $phone)
    {
        $url = config('services.telegram_checks.url');
        $app_id = config('services.telegram_checks.app_id');
        $api_hash = config('services.telegram_checks.api_hash');
        $session_name = config('services.telegram_checks.session_name');

        $payload = [
            "phone_numbers" => [$phone], // ✅ ONE NUMBER ONLY
            "app_id" => $app_id,
            "api_hash" => $api_hash,
            "session_name" => $session_name
        ];

        $response = Http::timeout(30)->post($url, $payload);

        if (!$response->successful()) {
            return; // ❌ skip if HTTP error
        }

        $data = $response->json();

        // ❌ API status false হলে skip
        if (!isset($data['status']) || $data['status'] !== true) {
            return;
        }

        // ✅ status true → store
        if (isset($data['data'][$phone])) {
            $this->storeTelegramData($phone, $data['data'][$phone], $data);
        }
    }

    private function storeTelegramData($phone, $info, $fullResponse)
    {
        $exists = $info['exists'] ?? false;

        TelegramCheck::updateOrCreate(
            ['phone' => $phone],
            [
                'username' => $info['username'] ?? null,
                'first_name' => $info['first_name'] ?? null,
                'last_name' => $info['last_name'] ?? null,
                'bot' => isset($info['bot']) ? (string)$info['bot'] : null,
                'verified' => isset($info['verified']) ? (string)$info['verified'] : null,
                'premium' => isset($info['premium']) ? (string)$info['premium'] : null,
                'temp' => isset($info['temp']) ? (string)$info['temp'] : null,
                'exists' => (string)$exists,
                'has_telegram' => $exists ? "true" : "false",
                'api_response' => $fullResponse,

                'created_by' => user()->id,
                'updated_by' => user()->id,
            ]
        );
    }

    public function export()
    {
        $phones = TelegramCheck::where('has_telegram', 'yes')
            ->latest()
            ->pluck('phone')
            ->implode("\n");

        $fileName = "telegram_numbers_" . date('Y_m_d') . ".txt";

        return response($phones)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename=$fileName");
    }
}
