<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class myAccountController extends Controller
{
    public function index() {
        return view('users.myAccount');
    }

    public function getProfil() {
        $user = auth()->user();
        return $user;
    }

    public function post(Request $request) {
        $user = $request->user;

        $validatedData = $request->validate([
            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:255',
            'user.telephone' => 'nullable|regex:/^\+?[0-9\s\-\(\)]*$/|max:20',
            'user.firstname' => 'nullable|string|max:255',
        ]);

        try {
            User::where('id', $user['id'])->update([
                'name' => $validatedData['user']['name'],
                'email' => $validatedData['user']['email'],
                'telephone' => $validatedData['user']['telephone'],
                'firstname' => $validatedData['user']['firstname'],
            ]);

            return response()->json(['statut' => 'ok']);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
