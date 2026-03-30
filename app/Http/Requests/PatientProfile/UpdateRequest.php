<?php

namespace App\Http\Requests\PatientProfile;

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
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'medical_notes' => 'nullable|string',
        ];
    }
}
