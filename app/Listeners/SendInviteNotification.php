<?php

namespace App\Listeners;

use App\Events\CoRaqiInvited;
use App\Services\NotificationService;

class SendInviteNotification
{
    /**
     * Handle the event.
     */
    public function handle(CoRaqiInvited $event): void
    {
        $participant = $event->participant;
        NotificationService::notify($participant->raqi->user, 'Co-Raqi Invitation', 'You have been invited to join a session.', $participant->appointment);
    }
}
