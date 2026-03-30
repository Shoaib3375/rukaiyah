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
        return [
            'bio' => 'nullable|string',
            'specialization' => 'nullable|string',
            'languages' => 'nullable|string',
        ];
    }
}
