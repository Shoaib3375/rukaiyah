<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\AppointmentConfirmed::class => [
            \App\Listeners\SendAppointmentNotification::class,
        ],
        \App\Events\CoRaqiInvited::class => [
            \App\Listeners\SendInviteNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
