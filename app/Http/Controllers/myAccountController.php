<?php

namespace App\Http\Controllers;

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
}
