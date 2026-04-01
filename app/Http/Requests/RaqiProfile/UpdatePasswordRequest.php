<?php

namespace App\Http\Requests\RaqiProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:api'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
