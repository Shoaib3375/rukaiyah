<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SessionParticipant\InviteRequest;
use App\Models\Appointment;
use App\Models\SessionParticipant;
use App\Services\SessionService;
use Illuminate\Http\JsonResponse;

class SessionParticipantController extends ApiController
{
    public function index(Appointment $appointment): JsonResponse
    {
        $this->authorize('view', $appointment);
        return $this->success($appointment->participants);
    }

    public function invite(InviteRequest $request, Appointment $appointment): JsonResponse
    {
        $this->authorize('invite', $appointment);
        $participant = SessionService::inviteCoRaqi($appointment, $request->raqi_id, auth('api')->user());
        return $this->success($participant, 'Co-Raqi invited successfully.', 201);
    }

    public function destroy(Appointment $appointment, SessionParticipant $participant): JsonResponse
    {
        $this->authorize('invite', $appointment); // Uses same authorization as invite (lead raqi)

        if ($participant->appointment_id !== $appointment->id) {
            return $this->error('Participant not found in this session.', 404);
        }

        $participant->delete();
        return $this->success(null, 'Co-Raqi removed successfully.');
    }
}
