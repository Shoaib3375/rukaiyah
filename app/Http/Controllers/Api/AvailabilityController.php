<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Availability\StoreRequest;
use App\Http\Requests\Availability\UpdateRequest;
use App\Models\Availability;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends ApiController
{
    public function index(): JsonResponse
    {
        $availabilities = auth('api')->user()->raqiProfile->availabilities;
        return $this->success($availabilities);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $availability = auth('api')->user()->raqiProfile->availabilities()->create($request->validated());
        return $this->success($availability, 'Availability created successfully.', 201);
    }

    public function show(Availability $availability): JsonResponse
    {
        $this->authorize('view', $availability);
        return $this->success($availability);
    }

    public function update(UpdateRequest $request, Availability $availability): JsonResponse
    {
        $this->authorize('update', $availability);
        $availability->update($request->validated());
        return $this->success($availability, 'Availability updated successfully.');
    }

    public function destroy(Availability $availability): JsonResponse
    {
        $this->authorize('delete', $availability);
        $availability->delete();
        return $this->success(null, 'Availability deleted successfully.');
    }
}
