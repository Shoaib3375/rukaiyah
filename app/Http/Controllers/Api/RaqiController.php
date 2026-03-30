<?php

namespace App\Http\Controllers\Api;

use App\Models\RaqiProfile;
use App\Services\AppointmentService;
use App\Enums\RaqiStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RaqiController extends ApiController
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $limit = min($request->query('limit', 12), 100);
        $raqis = RaqiProfile::select(['id', 'user_id', 'specialization', 'bio', 'is_approved', 'status', 'rating', 'total_reviews'])
            ->with('user:id,full_name,email,phone')
            ->where('is_approved', true)
            ->where('status', RaqiStatus::Active)
            ->latest('rating')
            ->paginate($limit);

        return $this->success($raqis);
    }

    public function show(RaqiProfile $raqi): JsonResponse
    {
        if (!$raqi->is_approved || $raqi->status !== RaqiStatus::Active) {
            return $this->error('Raqi not found or not active.', 404);
        }

        return $this->success($raqi->load(['user', 'availabilities', 'reviews.patient']));
    }

    public function availableSlots(Request $request, RaqiProfile $raqi): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'duration_minutes' => 'integer|min:15|max:240|nullable',
        ]);

        if (!$raqi->is_approved || $raqi->status !== RaqiStatus::Active) {
            return $this->error('Raqi not found or not active.', 404);
        }

        // Use smaller default duration to show more slot options
        $duration = $validated['duration_minutes'] ?? 30;
        $slots = $this->appointmentService->getAvailableSlots($raqi->id, $validated['date'], $duration);

        return $this->success([
            'date' => $validated['date'],
            'duration_minutes' => $duration,
            'slots' => $slots,
        ]);
    }
}
