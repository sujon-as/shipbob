<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'vip_id',
        'upgrade_text',
        'progress_in_percentage',
        'showing_amount',
        'authority_text',
    ];

    public static function rules()
    {
        return [
            'upgrade_text' => 'required|string',
            'progress_in_percentage' => 'required|integer|min:0|max:100',
            'showing_amount_text' => 'required|string',
            'authority_text' => 'required|string',
        ];
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'vip_id');
    }
}
