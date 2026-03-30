<?php

namespace App\Http\Controllers\Api;

use App\Models\SessionParticipant;
use Illuminate\Http\JsonResponse;
use App\Enums\InviteStatus;

class InviteController extends ApiController
{
    public function accept(string $token): JsonResponse
    {
        $participant = SessionParticipant::where('invite_token', $token)->firstOrFail();
        if ($participant->invite_status !== InviteStatus::Pending) {
            return $this->error('Invite already processed.');
        }
        $participant->update([
            'invite_status' => InviteStatus::Accepted,
            'joined_at' => now(),
        ]);
        return $this->success(null, 'Invite accepted.');
    }

    public function decline(string $token): JsonResponse
    {
        $participant = SessionParticipant::where('invite_token', $token)->firstOrFail();
        if ($participant->invite_status !== InviteStatus::Pending) {
            return $this->error('Invite already processed.');
        }
        $participant->update(['invite_status' => InviteStatus::Declined]);
        return $this->success(null, 'Invite declined.');
    }
}
