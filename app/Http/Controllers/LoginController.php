<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful'
            ], 200)->withCookie(cookie()->forever('XSRF-TOKEN', $request->session()->token()));
        }
    
        return response()->json([
            'message' => 'Identifiants incorrects.'
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout(); 

        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}