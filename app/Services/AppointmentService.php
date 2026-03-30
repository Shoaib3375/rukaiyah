<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\AppointmentStatus;
use App\Enums\ParticipantRole;
use App\Enums\InviteStatus;
use Carbon\Carbon;
use App\Jobs\SendAppointmentReminderJob;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function book(User $patient, array $data): Appointment
    {
        $this->checkAvailability($data['lead_raqi_id'], $data['scheduled_at'], $data['duration_minutes'] ?? 60);

        return DB::transaction(function () use ($patient, $data) {
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'lead_raqi_id' => $data['lead_raqi_id'],
                'session_type' => $data['session_type'],
                'status' => AppointmentStatus::Pending,
                'scheduled_at' => $data['scheduled_at'],
                'duration_minutes' => $data['duration_minutes'] ?? 60,
                'patient_notes' => $data['patient_notes'] ?? null,
            ]);

            $appointment->participants()->create([
                'raqi_id' => $data['lead_raqi_id'],
                'role' => ParticipantRole::Lead,
                'invite_status' => InviteStatus::Accepted,
                'joined_at' => now(),
            ]);

            event(new \App\Events\AppointmentConfirmed($appointment));

            return $appointment;
        });
    }

    public function cancel(Appointment $appointment): void
    {
        if (!$appointment->isCancellable()) {
            throw new \Exception('Cancellation requires at least 2 hours notice.');
        }
        $appointment->update(['status' => AppointmentStatus::Cancelled]);
        
        // NotificationService::notify($appointment->patient, 'Appointment Cancelled', '...', $appointment);
        // NotificationService::notify($appointment->leadRaqi->user, 'Appointment Cancelled', '...', $appointment);
    }

    public function getAvailableSlots(string $raqiId, string $date, int $durationMinutes = 60): array
    {
        $date = Carbon::parse($date)->startOfDay();
        // Convert Carbon's dayOfWeek (Sun=0) to app's format (Mon=0, Sun=6)
        $dayOfWeek = ($date->dayOfWeek + 6) % 7;

        // Get raqi's availability for this day
        $availabilities = $this->getRaqiAvailability($raqiId, $dayOfWeek);

        if ($availabilities->isEmpty()) {
            return [];
        }

        $slots = [];
        $duration = $durationMinutes;

        foreach ($availabilities as $availability) {
            if ($availability->is_blocked) {
                continue;
            }

            $slotStart = $date->copy()->setTimeFromTimeString($availability->slot_start);
            $slotEnd = $date->copy()->setTimeFromTimeString($availability->slot_end);

            // Generate 30-minute intervals for booking
            while ($slotStart->copy()->addMinutes($duration)->lte($slotEnd)) {
                $appointmentEnd = $slotStart->copy()->addMinutes($duration);

                // Check for conflicts with existing appointments
                $hasConflict = Appointment::where('lead_raqi_id', $raqiId)
                    ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
                    ->where(function ($q) use ($slotStart, $appointmentEnd) {
                        $q->whereBetween('scheduled_at', [$slotStart, $appointmentEnd->copy()->subSecond()])
                          ->orWhere(function ($q) use ($slotStart, $appointmentEnd) {
                              $q->whereRaw("scheduled_at + (duration_minutes * interval '1 minute') > ?", [$slotStart])
                                ->whereRaw("scheduled_at < ?", [$appointmentEnd]);
                          });
                    })->exists();

                if (!$hasConflict) {
                    $slots[] = [
                        'start' => $slotStart->toIso8601String(),
                        'end' => $appointmentEnd->toIso8601String(),
                        'display' => $slotStart->format('H:i') . ' – ' . $appointmentEnd->format('H:i'),
                    ];
                }

                $slotStart->addMinutes(30);
            }
        }

        return $slots;
    }

    private function getRaqiAvailability($raqiId, $dayOfWeek)
    {
        return \App\Models\Availability::where('raqi_id', $raqiId)
            ->where('day_of_week', $dayOfWeek)
            ->get();
    }

    private function checkAvailability(string $raqiId, string $scheduledAt, int $duration): void
    {
        $start = Carbon::parse($scheduledAt);
        $end   = $start->copy()->addMinutes($duration);

        $conflict = Appointment::where('lead_raqi_id', $raqiId)
            ->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('scheduled_at', [$start, $end])
                  ->orWhere(function ($q) use ($start, $end) {
                      $q->whereRaw("scheduled_at + (duration_minutes * interval '1 minute') BETWEEN ? AND ?", [$start, $end]);
                  });
            })->exists();

        if ($conflict) {
            throw new \Exception('Raqi is not available at the requested time.');
        }
    }
}
