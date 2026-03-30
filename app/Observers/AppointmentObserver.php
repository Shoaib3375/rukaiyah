<?php

namespace App\Observers;

use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        if ($appointment->scheduled_at->isFuture() && $appointment->scheduled_at->diffInMinutes(now()) > 60) {
            SendAppointmentReminderJob::dispatch($appointment)
                ->delay($appointment->scheduled_at->subHour());
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        // If scheduled_at changed, we might need to update the reminder.
        // For now, let's keep it simple as per work.md
    }
}
