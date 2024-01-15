<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'hourly_rate' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|in:EUR,USD,GBP',
        ];
    }
}


