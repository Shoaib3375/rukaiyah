<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Review;
use App\Models\SessionNote;
use App\Policies\AppointmentPolicy;
use App\Policies\AvailabilityPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SessionNotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Appointment::class => AppointmentPolicy::class,
        Availability::class => AvailabilityPolicy::class,
        Review::class => ReviewPolicy::class,
        SessionNote::class => SessionNotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
