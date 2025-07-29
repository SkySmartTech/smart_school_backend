<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('username', $credentials['username'])->first();

        if (!$user->status) {
            return response()->json(['message' => 'Your account is Unavailabile.'], 403);
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials!',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
        ], 201);
    }
}
