<?php

namespace App\Http\Requests\SessionNote;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
            'note_type' => 'required|in:ruqyah_log,observation,recommendation,chat',
        ];
    }
}
