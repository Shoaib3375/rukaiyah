<?php

namespace App\Http\Requests\Availability;

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
            'day_of_week' => 'sometimes|integer|min:0|max:6',
            'slot_start' => 'sometimes|date_format:H:i',
            'slot_end' => 'sometimes|date_format:H:i|after:slot_start',
            'is_blocked' => 'boolean',
        ];
    }
}
