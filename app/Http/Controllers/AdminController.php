<?php

namespace App\Http\Controllers;

use App\Models\Permission;
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
            $roles = Role::with('permissions')->get();
            $permissions = Permission::all();
            return response()->json(['statut' => 'ok', 'users' => $users, 'roles' => $roles, 'permissions' => $permissions]);
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
                'veryfied' => $user['veryfied'],
            ]);
            $user = User::find($user['id']);
            $user->roles()->detach();
            $user->roles()->attach($roleId);
            return response()->json(['statut' => 'ok']);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function deletePermissionRole(Request $request){
        try {
            $permissionId = $request->input('permissionId');
            $roleId = $request->input('id');
            $role = Role::find($roleId);
            $role->permissions()->detach($permissionId);
            return response()->json(['statut' => 'ok', 'role' => $role]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function addPermissionRole(Request $request){
        try {
            $permissionId = $request->input('permissionId');
            $roleId = $request->input('id');
            $role = Role::find($roleId);
            $role->permissions()->attach($permissionId);
            return response()->json(['statut' => 'ok', 'role' => $role]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function addRole(Request $request){
        try {
            $name = $request->input('name');
            $role = Role::create(['name' => $name]);
            $roles = Role::with('permissions')->get();
            return response()->json(['statut' => 'ok', 'roles' => $roles]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function addPermission (Request $request){
        try {
            $name = $request->input('permission');
            $permission = Permission::create(['permission' => $name]);
            $permissions = Permission::all();
            return response()->json(['statut' => 'ok', 'permissions' => $permissions]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function deletePermission(Request $request){
        try {
            $id = $request->input('id');
            $permission = Permission::find($id);
            $permission->delete();
            $permissions = Permission::all();
            return response()->json(['statut' => 'ok', 'permissions' => $permissions]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }

    function deleteRole(Request $request){
        try {
            $id = $request->input('id');
            $role = Role::find($id);
            $role->delete();
            $roles = Role::with('permissions')->get();
            return response()->json(['statut' => 'ok', 'roles' => $roles]);
        } catch (\Throwable $th) {
            return response()->json(['statut' => 'error', 'message' => $th->getMessage()]);
        }
    }
}
