<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => 'required|string',
            'is_default' => 'required|boolean',
            'bg_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }

    public static function updateRules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => 'required|string',
            'is_default' => 'required|boolean',
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ];
    }
    public function assignLevel()
    {
        return $this->hasMany(AssignLevel::class);
    }
    public function vipDetails()
    {
        return $this->hasOne(VipDetail::class, 'vip_id');
    }
}
