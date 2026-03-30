<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Enums\RaqiStatus;
use App\Models\Appointment;
use App\Models\RaqiProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminStatsController extends ApiController
{
    public function index(): JsonResponse
    {
        $stats = cache()->remember('admin.stats', 300, function () {
            return [
                'totalUsers'         => User::count(),
                'activeRaqis'        => RaqiProfile::where('is_approved', true)->where('status', RaqiStatus::Active)->count(),
                'pendingRaqis'       => RaqiProfile::where('is_approved', false)->count(),
                'totalAppointments'  => Appointment::count(),
            ];
        });

        return $this->success($stats);
    }
}
