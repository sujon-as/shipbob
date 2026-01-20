<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TelegramChecked implements ShouldBroadcastNow
{
    public $phone;

    public function __construct($phone = null)
    {
        $this->phone = $phone;
    }

    public function broadcastOn()
    {
        return new Channel('telegram');
    }

    public function broadcastAs()
    {
        return 'telegram.updated';
    }

    public function broadcastWith()
    {
        return [
            'phone' => $this->phone,
            'time' => now()->toDateTimeString(),
        ];
    }
}
