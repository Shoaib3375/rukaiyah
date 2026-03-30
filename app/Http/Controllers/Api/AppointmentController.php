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

    public function patientIndex(): JsonResponse
    {
        $appointments = auth('api')->user()->appointments()->with('leadRaqi.user')->get();
        return $this->success($appointments);
    }

    public function raqiIndex(): JsonResponse
    {
        $appointments = auth('api')->user()->raqiProfile->appointments()->with('patient')->get();
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
        return $this->success($appointment->load(['patient', 'leadRaqi.user', 'participants.raqi.user']));
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
}
