<?php

namespace App\Jobs;

use App\Events\TelegramChecked;
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
        try {
            $url = config('services.telegram_checks.url');

            $body = [
                "phone_numbers" => [$this->phone],
                "app_id" => $this->payload['app_id'],
                "api_hash" => $this->payload['api_hash'],
                "session_name" => $this->payload['session_name'],
            ];

            $response = Http::timeout(120)->post($url, $body);

            if ($response->status() === 200) {

                $data = $response->json();

                if (isset($data['data'][$this->phone])) {

                    $info = $data['data'][$this->phone];

                    $telegramCheck = TelegramCheck::create([
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
                    event(new TelegramChecked($this->phone));
                }
            }
            // If not 200 → do nothing (skip)
        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            \Log::error("Telegram check failed for phone {$this->phone}: " . $e->getMessage());

        }
    }
}
