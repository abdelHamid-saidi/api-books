<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|max:255",
            "email" => "required|email|unique:users",
            "password" => "required|min:8"
        ]);

        $user = User::create($validated);

        return response()->json([
            "message" => "User created",
            "user" => $user
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json(["message" => "Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken("api-token")->plainTextToken;

        return response()->json(["access_token" => $token, "user" => $user], Response::HTTP_ACCEPTED);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
