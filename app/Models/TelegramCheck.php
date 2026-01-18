<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class TelegramCheck extends Model
{
    use HasFactory;

    protected $table = 'telegram_checks';

    protected $fillable = [
        'phone',
        'username',
        'first_name',
        'last_name',
        'bot',
        'verified',
        'premium',
        'temp',
        'exists',
        'error',
        'api_response',
        'has_telegram',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'api_response' => 'array',
        // If 'has_telegram' is intended to behave as a boolean (e.g., storing 'true'/'false' or '1'/'0'),
        // you can uncomment the following line to cast it automatically:
        // 'has_telegram' => 'boolean',
    ];

    /**
     * Get the user who created the record.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the record.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
