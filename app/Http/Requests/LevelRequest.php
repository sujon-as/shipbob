<?php

namespace App\Http\Requests;

use App\Models\Level;
use App\Models\Package;
use App\Models\VipDetail;
use Illuminate\Foundation\Http\FormRequest;

class LevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Get the route name and apply null-safe operator
        $routeName = $this->route()?->getName();

        if ($routeName === 'vips.update') {
            return Level::updateRules();
        }

        if ($routeName === 'vips.updateDetails') {
            return VipDetail::rules();
        }
        return Level::rules();
    }
}
