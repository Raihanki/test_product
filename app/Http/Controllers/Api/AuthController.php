<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $loginRequest->validated();
        try {
            $token = $this->authService->login($loginRequest);
            return response()->json([
                "status" => 200,
                "data" => [
                    "token" => $token
                ]
            ], 200);
        } catch (UnauthorizedException $e) {
            return response()->json([
                "status" => 400,
                "data" => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            Log::error("Error When Login : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" =>  "Internal Server Error"
            ], 500);
        }
    }

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $registerRequest->validated();
        try {
            $user = $this->authService->register($registerRequest);
            return response()->json([
                "status" => 200,
                "data" => [
                    "user" => $user
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error("Error When Register : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" =>  "Internal Server Error"
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return response()->json([
                "status" => 200,
                "data" => "Logout Success"
            ], 200);
        } catch (Exception $e) {
            Log::error("Error When Logout : " . $e->getMessage());
            return response()->json([
                "status" => 500,
                "data" =>  "Internal Server Error"
            ], 500);
        }
    }
}
