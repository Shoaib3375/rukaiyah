<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\FollowUp\StoreRequest;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;

class FollowUpController extends ApiController
{
    public function store(StoreRequest $request, Appointment $appointment): JsonResponse
    {
        $this->authorize('update', $appointment);
        $followUp = $appointment->followUp()->create([
            'raqi_id' => auth('api')->user()->raqiProfile->id,
            'recommendation' => $request->recommendation,
            'follow_up_date' => $request->follow_up_date,
        ]);
        return $this->success($followUp, 'Follow-up scheduled successfully.', 201);
    }
}
