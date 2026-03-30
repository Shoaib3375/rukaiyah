<?php

namespace App\Services;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Notification;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;

class NotificationService
{
    public static function notify(User $user, string $title, string $body, ?Appointment $appointment = null, string $channel = 'email'): void
    {
        $record = Notification::create([
            'user_id'        => $user->id,
            'appointment_id' => $appointment?->id,
            'title'          => $title,
            'body'           => $body,
            'channel'        => $channel,
        ]);

        match ($channel) {
            'email' => SendEmailJob::dispatch($record),
            'sms'   => SendSmsJob::dispatch($record),
            default => null,
        };

        broadcast(new \App\Events\NewNotification($record))->toOthers();
    }
}
