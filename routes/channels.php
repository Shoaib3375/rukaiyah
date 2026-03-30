<?php

use App\Models\Appointment;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});

Broadcast::channel('appointment.{appointmentId}', function ($user, $appointmentId) {
    $appt = Appointment::find($appointmentId);
    if (!$appt) return false;

    return $user->id === $appt->patient_id
        || $appt->participants->contains('raqi_id', $user->raqiProfile?->id);
});
