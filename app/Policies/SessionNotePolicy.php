<?php

namespace App\Policies;

use App\Models\SessionNote;
use App\Models\User;

class SessionNotePolicy
{
    public function view(User $user, SessionNote $sessionNote): bool
    {
        return $user->id === $sessionNote->appointment->patient_id
            || $user->raqiProfile?->id === $sessionNote->raqi_id
            || $sessionNote->appointment->participants->contains('raqi_id', $user->raqiProfile?->id);
    }

    public function create(User $user): bool
    {
        return $user->raqiProfile !== null;
    }
}
