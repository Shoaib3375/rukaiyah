<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\AppointmentStatus;

class AppointmentPolicy
{
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id
            || $user->raqiProfile?->id === $appointment->lead_raqi_id
            || $appointment->participants->contains('raqi_id', $user->raqiProfile?->id)
            || $user->role === UserRole::Admin;
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->raqiProfile?->id === $appointment->lead_raqi_id
            || $user->role === UserRole::Admin;
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->patient_id && $appointment->isCancellable();
    }

    public function complete(User $user, Appointment $appointment): bool
    {
        return $user->raqiProfile?->id === $appointment->lead_raqi_id;
    }

    public function invite(User $user, Appointment $appointment): bool
    {
        return $user->raqiProfile?->id === $appointment->lead_raqi_id;
    }
}
