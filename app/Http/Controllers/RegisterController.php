<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function addUser(Request $request)
    {
        // Messages de validation personnalisés
        $messages = [
            'firstname.required' => 'Le prénom est requis.',
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Votre mot de passe est trop court, il doit faire au moins 12 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.',
        ];

        // Validation des données avec les règles et les messages personnalisés
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:12', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', 
            ],
        ], $messages);

        // Si la validation échoue, renvoyer les erreurs dans une réponse JSON
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);  // Code HTTP 422 Unprocessable Entity
        }

        // Fonction pour générer un code à 4 chiffres
        function generateFourDigitCode() {
            return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        // Création du nouvel utilisateur
        $validatedData = $validator->validated();

        $user = new User();
        $user->firstname = $validatedData['firstname'];
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->verif_code = generateFourDigitCode();
        $user->save();

        // Assigner le rôle par défaut "user"
        $defaultRole = Role::firstOrCreate(['name' => 'user']);
        $user->roles()->attach($defaultRole);

        // Envoi du mail de vérification
        SendMailController::SendVerifMail(['to' => $validatedData['email']]);

        // Réponse succès
        return response()->json(['message' => 'Utilisateur enregistré avec succès'], 201);
    }
}
