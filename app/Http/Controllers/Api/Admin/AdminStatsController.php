<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminStatsController extends ApiController
{
    public function index(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
        ];
        return $this->success($stats);
    }
}
