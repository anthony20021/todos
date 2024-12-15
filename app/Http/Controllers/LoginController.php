<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        // Validation des informations d'identification
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Récupération de l'utilisateur par email
        $user = User::where('email', $credentials['email'])->first();

        // Vérification si l'utilisateur existe et a confirmé son email
        if ($user && !$user->veryfied) {
            return response()->json(['message' => 'Veuillez confirmer votre email', 'code' => 'unverified'], 201);
        }

        // Tentative d'authentification si l'utilisateur a confirmé son email
        if ($user && Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $cookie = cookie()->forever('XSRF-TOKEN', $request->session()->token());

            return response()->json(['message' => 'Login successful', 'code' => 'ok'])
                ->withCookie($cookie);
        }

        return response()->json(['message' => 'Identifiant incorrect', 'code' => 'unauthorized'], 201);
    }

    public function verif(Request $request): JsonResponse
    {
        // Validation des informations d'identification
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'verification_code' => ['required'],
        ]);

        // Récupération de l'utilisateur par email
        $user = User::where('email', $credentials['email'])->first();

        // Vérification du code de confirmation
        if ($user->verif_code == $credentials['verification_code']) {
            $user->verif_code = null;
            $user->veryfied = true;
            $user->save();

            return response()->json(['message' => 'Email confirmé', 'code' => 'verified'], 200);
        }

        return response()->json(['message' => 'Code incorrect', 'code' => 'unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public static function generateFourDigitCode() {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function resendCode(Request $request): JsonResponse
    {

        try {
            $user = User::where('email', $request['email'])->first();

            if ($user) {
                $user->verif_code = self::generateFourDigitCode();
                $user->save();

                // Envoi de l'email de vérification
                SendMailController::SendVerifMdp(['to' => $request['email']]);

                return response()->json(['message' => 'Code renvoyé', 'code' => 'verified'], 200);
            }

            return response()->json(['message' => 'Utilisateur introuvable', 'code' => 'not_found'], 404);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => 'error'], 500);
        }
    }

    public function changeCodeMdp(Request $request){
        $messages = [
            'code.required' => 'Le code est requis.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'mdp.required' => 'Le mot de passe est requis.',
            'mdp.min' => 'Votre mot de passe est trop court, il doit faire au moins 12 caractères.',
            'mdp.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.',
        ];

        // Validation des données avec les règles et les messages personnalisés
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'email' => ['required', 'email'],
            'mdp' => [
                'required',
                'min:12', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', 
            ],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => 'Erreur de validation', 'errors' => $validator->errors()], 422);
        }

        $code = $request['code'];
        $email = $request['email'];
        $new_mdp = $request['mdp'];

        try {
            $user = User::where('email', $email)->first();
            if ($user && $user->verif_code == $code) {
                $user->password = password_hash($new_mdp, PASSWORD_BCRYPT);
                $user->verif_code = null;
                $user->save();

                return response()->json(['message' => 'Mot de passe modifié', 'code' => 'ok'], 200);
            }
            $user->verif_code = null;
            $user->save();
            return response()->json(['message' => 'Code invalide', 'code' => 'not_found'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => 'error'], 500);
        }

    }

    public function verifyCodeMdp(Request $request){
        $code = $request['code'];
        $email = $request['email'];

        try {
            $user = User::where('email', $email)->first();
            if ($user && $user->verif_code == $code) {
                $user->save();

                return response()->json(['message' => 'Code valide', 'code' => 'ok'], 200);
            }
            $user->verif_code = null;
            $user->save();
            return response()->json(['message' => 'Code invalide', 'code' => 'not_found'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'code' => 'error'], 500);
        }
    }

    public function sendCodeMdp(Request $request){

        $email = $request['email'];

        try {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->verif_code = self::generateFourDigitCode();
                $user->save();

                // Envoi de l'email de vérification
                SendMailController::SendVerifMdp(['to' => $email]);

                return response()->json(['message' => 'Code renvoyé', 'code' => 'ok'], 200);
            }
            else{
                return response()->json(['message' => 'Code renvoyé', 'code' => 'ok'], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Code renvoyé', 'code' => 'ok'], 200);
        }
    }
}
