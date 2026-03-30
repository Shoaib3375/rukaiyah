<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminUserController extends ApiController
{
    public function index(): JsonResponse
    {
        $limit = request()->input('limit', 15);
        $limit = min($limit, 100); // Max 100 per page
        
        $users = User::select(['id', 'full_name', 'email', 'phone', 'role', 'is_active', 'created_at'])
            ->with(['patientProfile:id,user_id', 'raqiProfile:id,user_id,is_approved,status'])
            ->latest('created_at')
            ->paginate($limit);
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
