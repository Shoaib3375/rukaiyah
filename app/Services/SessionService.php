<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Models\RaqiProfile;
use App\Models\SessionParticipant;
use App\Enums\AppointmentStatus;
use App\Enums\ParticipantRole;
use App\Enums\InviteStatus;
use App\Enums\RaqiStatus;

class SessionService
{
    public function inviteCoRaqi(Appointment $appointment, string $raqiId, User $leadUser): SessionParticipant
    {
        if ($appointment->lead_raqi_id !== $leadUser->raqiProfile->id) {
            throw new \Exception('Only the lead Raqi can invite co-Raqis.');
        }

        $raqi = RaqiProfile::where('id', $raqiId)
            ->where('is_approved', true)
            ->where('status', RaqiStatus::Active)
            ->firstOrFail();

        $this->checkCoRaqiConflict($raqi->id, $appointment);

        $participant = $appointment->participants()->create([
            'raqi_id'       => $raqi->id,
            'role'          => ParticipantRole::CoRaqi,
            'invite_status' => InviteStatus::Pending,
            'invited_at'    => now(),
        ]);

        event(new \App\Events\CoRaqiInvited($participant));

        return $participant;
    }

    public function closeSession(Appointment $appointment, User $leadUser): void
    {
        if ($appointment->lead_raqi_id !== $leadUser->raqiProfile->id) {
            throw new \Exception('Only the lead Raqi can close the session.');
        }
        $appointment->update(['status' => AppointmentStatus::Completed]);
        event(new \App\Events\SessionClosed($appointment));
    }

    private function checkCoRaqiConflict(string $raqiId, Appointment $appointment): void
    {
        $conflict = SessionParticipant::where('raqi_id', $raqiId)
            ->whereHas('appointment', fn($q) => $q
                ->where('id', '!=', $appointment->id)
                ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
                ->where('scheduled_at', $appointment->scheduled_at)
            )->exists();

        if ($conflict) {
            throw new \Exception('Co-Raqi has a conflicting appointment at this time.');
        }
    }
}
