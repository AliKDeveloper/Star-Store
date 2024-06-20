<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        //$request->session()->regenerate();

        $user = User::where('email', $request->email)->firstOrFail();

        $random = Str::random(255);
        $token = $user->createToken($random)->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer', 'message' => 'Login Successfully'], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
//        Auth::guard('web')->logout();
//
//        $request->session()->invalidate();
//
//        $request->session()->regenerateToken();

        //Delete the current token
        //$request->user()->currentAccessToken()->delete();

        //Delete all tokens
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout Successfully'], 200);
    }
}
