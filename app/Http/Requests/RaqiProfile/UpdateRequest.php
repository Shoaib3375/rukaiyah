<?php

namespace App\Http\Requests\RaqiProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth('api')->user();
        return [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($user ? $user->id : 'NULL') . ',id',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string',
            'languages' => 'nullable|string',
        ];
    }
}
