<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'currency' => 'required|in:EUR,USD,GBP',
        ];
    }
}


