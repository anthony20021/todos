<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $user = Auth::user();
            if($user['veryfied']){
                $request->session()->regenerate();
                $cookie = cookie()->forever('XSRF-TOKEN', $request->session()->token());

                return response()->json(['message' => 'Login successful'])
                    ->withCookie($cookie);
            }
            else{
                return response()->json(['message' => 'Veuillez confirmer votre email', 'code' => 'unverified'], 201);
            }
        }

        return response()->json(['message' => 'Indentifiant incorrect', 'code' => 'unauthorized'], 201);
    }

    public function verif(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $verif = $request->verification_code;
            $user = User::where('email', $credentials['email'])->first();
            if($user['verif_code'] == $verif){
                $user->verif_code = null;
                $user->veryfied = true;
                $user->save();
                return response()->json(['message' => 'Email confirme', 'code' => 'verified'], 201);
            }
            else{
                return response()->json(['message' => 'Code incorrect', 'code' => 'unauthorized'], 201);
            }
        }
        else{
            return response()->json(['message' => 'Code incorrect', 'code' => 'unauthorized'], 201);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function resendCode(Request $request){
        function generateFourDigitCode() {
            return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        try {
            $user = User::where('email', $request['email'])->first();
            $user->verif_code = generateFourDigitCode();
            $user->save();
            SendMailController::SendVerifMail(['to' => $request['email']]);
            return response()->json(['message' => 'Code renvoyé', 'code' => 'verified'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => 'unauthorized'], 201);
        }
    }
}
