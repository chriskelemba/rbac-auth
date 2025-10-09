<?php

namespace RbacAuth\Http\Controllers;

use RbacAuth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RbacAuth\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::with(['roles.permissions'])
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return sendApiError('The provided credentials are incorrect.', 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return sendApiResponse([
            'token' => $token,
            'user'  => new UserResource($user),
        ], 'Login successful');
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return sendApiResponse([
            'user' => new UserResource($user),
        ], 'Current user fetched successfully');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return sendApiResponse([], 'Logged out successfully');
    }
}