<?php

namespace App\Jobs;

use App\Models\TelegramCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckTelegramNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $payload;

    public function __construct($phone, $payload)
    {
        $this->phone = $phone;
        $this->payload = $payload;
    }

    public function handle()
    {
        $url = config('services.telegram_checks.url');

        $body = [
            "phone_numbers" => [$this->phone],
            "app_id" => $this->payload['app_id'],
            "api_hash" => $this->payload['api_hash'],
            "session_name" => $this->payload['session_name'],
        ];

        $response = Http::timeout(60)->post($url, $body);

        if ($response->status() === 200) {

            $data = $response->json();

            if (isset($data['data'][$this->phone])) {

                $info = $data['data'][$this->phone];

                TelegramCheck::create([
                    'phone' => $this->phone,
                    'username' => $info['username'] ?? null,
                    'first_name' => $info['first_name'] ?? null,
                    'last_name' => $info['last_name'] ?? null,
                    'bot' => $info['bot'] ?? null,
                    'verified' => $info['verified'] ?? null,
                    'premium' => $info['premium'] ?? null,
                    'temp' => $info['temp'] ?? null,
                    'exists' => $info['exists'] ? 'yes' : 'no',
                    'has_telegram' => $info['exists'] ? 'yes' : 'no',
                    'api_response' => json_encode($data),
//                    'created_by' => user()->id,
//                    'updated_by' => user()->id,
                ]);
            }
        }
        // If not 200 → do nothing (skip)
    }
}
