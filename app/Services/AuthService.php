<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

interface AuthService
{
    public function login(LoginRequest $loginRequest): string;

    public function register(RegisterRequest $registerRequest): User;

    public function logout(User $user): void;
}
