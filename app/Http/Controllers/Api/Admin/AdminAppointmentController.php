<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class AdminAppointmentController extends ApiController
{
    public function index(): JsonResponse
    {
        $limit = request()->input('limit', 15);
        $limit = min($limit, 100); // Max 100 per page
        
        $appointments = Appointment::select(['id', 'patient_id', 'lead_raqi_id', 'session_type', 'status', 'scheduled_at', 'created_at'])
            ->with([
                'patient:id,user_id',
                'patient.user:id,full_name,email',
                'leadRaqi:id,user_id',
                'leadRaqi.user:id,full_name,email'
            ])
            ->latest('scheduled_at')
            ->paginate($limit);
        return $this->success($appointments);
    }

    public function update(Appointment $appointment): JsonResponse
    {
        // Assuming update status or something
        // For now, placeholder
        return $this->success($appointment);
    }
}
