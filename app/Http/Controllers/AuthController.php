<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 401);
        }

        $token = Auth::user()->createToken('auth');

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'token' => $token->plainTextToken,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(3)->mixedCase()->numbers()],
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $token = User::create($validated)->createToken('auth');

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout',
        ]);
    }
}
