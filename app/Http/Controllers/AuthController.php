<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
{
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return response(new \App\Http\Resources\UserResource($user), 201);
}
public function login(LoginRequest $request)
{
    if (!auth()->attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = auth()->user()->createToken('access_token')->plainTextToken;

    return response()->json(['token' => $token], 200);
}
public function me()
{
    return new \App\Http\Resources\UserResource(auth()->user());
}
public function logout()
{
    auth()->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
}
public function tokens()
{
    return auth()->user()->tokens;
}
public function revokeAllTokens()
{
    auth()->user()->tokens()->delete();

    return response()->json(['message' => 'All tokens revoked'], 200);
}
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    if (!Hash::check($request->current_password, auth()->user()->password)) {
        return response()->json(['message' => 'Current password is incorrect'], 400);
    }

    auth()->user()->update([
        'password' => bcrypt($request->new_password),
    ]);

    return response()->json(['message' => 'Password changed successfully'], 200);
}
}
