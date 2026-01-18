<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'trial_amount',
        'frozen_amount',
        'no_of_trial_task',
        'task_timing',
        'telegram_group_link',
        'company_name',
        'company_logo',
        'daily_task_limit',
        'rtt_trial_balance',
        'is_site_active',
        'min_cash_out_amount',
        'maintain_desc_text',
        'maintain_title_text',
        'trail_balance_text',
        'reserved_amount_text',

        'min_ratings',
        'order_success_mgs_1',
        'order_success_mgs_2',
        'order_btn_text',
        'rating_text',

        'vip_bg_image',
        'vip_mgs',
    ];
}
