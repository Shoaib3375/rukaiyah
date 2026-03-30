<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class AdminAppointmentController extends ApiController
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['patient', 'leadRaqi.user'])->paginate();
        return $this->success($appointments);
    }

    public function update(Appointment $appointment): JsonResponse
    {
        // Assuming update status or something
        // For now, placeholder
        return $this->success($appointment);
    }
}
