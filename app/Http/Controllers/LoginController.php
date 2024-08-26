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
            $cookie = cookie()->forever('XSRF-TOKEN', $request->session()->token());

            return response()->json(['message' => 'Login successful'])
                             ->withCookie($cookie);
        }

        return response()->json(['message' => 'Indentifiant incorrect'], 401);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
