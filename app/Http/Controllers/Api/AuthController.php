<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user  = AuthService::register($request->validated());
        $token = auth('api')->login($user);
        return $this->success($this->tokenPayload($token, $user), 'Registered successfully.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = auth('api')->attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials.', 401);
        }
        return $this->success($this->tokenPayload($token, auth('api')->user()));
    }

    public function refresh(): JsonResponse
    {
        return $this->success($this->tokenPayload(auth('api')->refresh()));
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->success(null, 'Logged out successfully.');
    }

    public function me(): JsonResponse
    {
        return $this->success(auth('api')->user());
    }

    private function tokenPayload(string $token, $user = null): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => $user,
        ];
    }
}
