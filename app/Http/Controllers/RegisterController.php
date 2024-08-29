<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function addUser(Request $request)
    {
        function generateFourDigitCode() {
            return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->firstname = $validatedData['firstname'];
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->verif_code = generateFourDigitCode();
        $user->save();

        $defaultRole = Role::firstOrCreate(['name' => 'user']);

        $user->roles()->attach($defaultRole);

        SendMailController::SendVerifMail(['to' => $validatedData['email']]);

        return response()->json(['message' => 'Utilisateur enregistré avec succès'], 201);
    }

}
