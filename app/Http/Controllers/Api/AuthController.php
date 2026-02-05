<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService){}

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $result = $this->authService->register($validatedData);

        return $this->successResponse($result, 'User registered successfully', 201);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $result = $this->authService->login($validatedData);
            return $this->successResponse($result, 'Login successful');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), 'Invalid credentials', 422);
        }
    }

    public function me(Request $request)
    {
        return $this->successResponse($request->user()->load('profile'), 'User profile retrieved successfully');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validatedData = $request->validated();

        $this->authService->resetPassword($validatedData['email']);

        return $this->successResponse(null, 'Password reset successfully');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->successResponse(null, 'Logged out successfully');
    }

    public function refresh(Request $request)
    {
        $result = $this->authService->refresh($request->user());

        return $this->successResponse($result, 'Token refreshed successfully');
    }

}
