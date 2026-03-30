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
        return [
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'medical_notes' => 'nullable|string',
        ];
    }
}
