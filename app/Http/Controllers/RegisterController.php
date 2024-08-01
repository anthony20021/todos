<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class RegisterController extends Controller
{
    public function addUser(Request $request)
    {
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

        $user->save(); 

        return response()->json(['message' => 'Utilisateur enregistré avec succès'], 201);
    }
}