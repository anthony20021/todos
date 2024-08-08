<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function index(){
        return view('admin.index');
    }
    function getUsers(){
        try {
            $users = User::with('roles')->get();
            $roles = Role::all();
            return response()->json(['statut' => 'ok', 'users' => $users, 'roles' => $roles]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function deleteUser(Request $request){
        try {
            $id = $request->input('id');
            $user = User::find($id);
            $user->delete();
            return response()->json(['statut' => 'ok']);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function modifUser(Request $request){
        $user = $request->input('user');
        $roleId = $user['roles'][0]['id'];
        try {
            User::where('id', $user['id'])->update([
                'name' => $user['name'],
                'email' => $user['email'],
                'telephone' => $user['telephone'],
                'firstname' => $user['firstname'],
            ]);
            $user = User::find($user['id']);
            $user->roles()->detach();
            $user->roles()->attach($roleId);
            return response()->json(['statut' => 'ok']);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
