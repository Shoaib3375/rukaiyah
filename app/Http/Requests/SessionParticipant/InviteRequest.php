<?php

namespace App\Http\Requests\SessionParticipant;

use Illuminate\Foundation\Http\FormRequest;

class InviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'raqi_id' => 'required|uuid|exists:raqi_profiles,id',
        ];
    }
}
