<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Services\NotificationService;

class SendAppointmentNotification
{
    /**
     * Handle the event.
     */
    public function handle(AppointmentConfirmed $event): void
    {
        $appointment = $event->appointment;
        NotificationService::notify($appointment->patient, 'Appointment Confirmed', 'Your appointment has been confirmed.', $appointment);
        NotificationService::notify($appointment->leadRaqi->user, 'New Appointment', 'You have a new appointment.', $appointment);
    }
}
