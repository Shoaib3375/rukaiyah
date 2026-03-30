<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SessionNote\StoreRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class SessionNoteController extends ApiController
{
    public function index(Appointment $appointment): JsonResponse
    {
        $this->authorize('view', $appointment);
        return $this->success($appointment->notes);
    }

    public function store(StoreRequest $request, Appointment $appointment): JsonResponse
    {
        $this->authorize('update', $appointment); // Assuming notes can be added by participants
        $note = $appointment->notes()->create([
            'raqi_id' => auth('api')->user()->raqiProfile->id,
            'content' => $request->content,
            'note_type' => $request->note_type,
        ]);
        event(new \App\Events\SessionNoteAdded($note));
        return $this->success($note, 'Note added successfully.', 201);
    }
}
