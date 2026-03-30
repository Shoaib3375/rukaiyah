<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminUserController extends ApiController
{
    public function index(): JsonResponse
    {
        $users = User::with(['patientProfile', 'raqiProfile'])->paginate();
        return $this->success($users);
    }

    public function show(User $user): JsonResponse
    {
        return $this->success($user->load(['patientProfile', 'raqiProfile']));
    }

    public function activate(User $user): JsonResponse
    {
        $user->update(['is_active' => !$user->is_active]);
        return $this->success(null, 'User status updated.');
    }
}
