<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Review\StoreRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class ReviewController extends ApiController
{
    public function store(StoreRequest $request): JsonResponse
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
        $this->authorize('create', $appointment); // Need to define in policy

        $review = $appointment->review()->create([
            'patient_id' => auth('api')->id(),
            'raqi_id' => $appointment->lead_raqi_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return $this->success($review, 'Review submitted successfully.', 201);
    }
}
