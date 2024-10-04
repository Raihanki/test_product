<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthServiceImpl implements AuthService
{
    public function login(LoginRequest $loginRequest): string
    {
        $user = User::where('email', $loginRequest->email)->first();
        if (!$user || !Hash::check($loginRequest->password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        $user->tokens()->delete();
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function register(RegisterRequest $registerRequest): User
    {
        return User::create([
            'name' => $registerRequest->name,
            'email' => $registerRequest->email,
            'password' => Hash::make($registerRequest->password),
        ]);
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
