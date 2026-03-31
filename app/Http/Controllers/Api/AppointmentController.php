<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends ApiController
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function patientIndex(Request $request): JsonResponse
    {
        $limit = min($request->query('limit', 10), 100);
        $appointments = auth('api')->user()->appointments()
            ->select(['id', 'patient_id', 'lead_raqi_id', 'session_type', 'status', 'scheduled_at', 'duration_minutes', 'patient_notes', 'created_at'])
            ->with('leadRaqi:id,user_id', 'leadRaqi.user:id,full_name')
            ->latest('scheduled_at')
            ->paginate($limit);
        return $this->success($appointments);
    }

    public function raqiIndex(Request $request): JsonResponse
    {
        $limit = min($request->query('limit', 10), 100);
        $appointments = auth('api')->user()->raqiProfile->appointments()
            ->select(['id', 'patient_id', 'lead_raqi_id', 'session_type', 'status', 'scheduled_at', 'duration_minutes', 'created_at'])
            ->with('patient:id,full_name')
            ->latest('scheduled_at')
            ->paginate($limit);
        return $this->success($appointments);
    }

    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->book(auth('api')->user(), $request->validated());
            return $this->success($appointment, 'Appointment booked successfully.', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show(Appointment $appointment): JsonResponse
    {
        $this->authorize('view', $appointment);
        return $this->success($appointment->load(['patient', 'leadRaqi.user', 'participants.raqi.user', 'notes.raqi.user', 'followUp']));
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $this->authorize('update', $appointment);
        $validated = $request->validate([
            'meeting_link' => 'nullable|url|max:255',
            'patient_instructions' => 'nullable|string',
            'raqi_notes' => 'nullable|string',
            'ailment' => 'nullable|string|max:255',
        ]);

        $appointment->update($validated);
        return $this->success($appointment, 'Appointment updated successfully.');
    }

    public function cancel(Appointment $appointment): JsonResponse
    {
        $this->authorize('cancel', $appointment);

        try {
            $this->appointmentService->cancel($appointment);
            return $this->success(null, 'Appointment cancelled successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function accept(Appointment $appointment): JsonResponse
    {
        $this->authorize('update', $appointment);
        $appointment->update(['status' => 'confirmed']);
        return $this->success(null, 'Appointment accepted.');
    }

    public function decline(Appointment $appointment): JsonResponse
    {
        $this->authorize('update', $appointment);
        $appointment->update(['status' => 'cancelled']);
        return $this->success(null, 'Appointment declined.');
    }

    public function complete(Appointment $appointment): JsonResponse
    {
        $this->authorize('complete', $appointment);
        $appointment->update(['status' => 'completed']);
        return $this->success(null, 'Appointment completed.');
    }
}
