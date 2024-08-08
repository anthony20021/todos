<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\myAccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home.home');
});
Route::get('/register', function () {
    return view('users.register');
});
Route::get('/login', function () {
    return view('users.login');
});

Route::post('/register/adduser', [RegisterController::class, 'addUser']);
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');;
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard.dashboard');
    });

    // Routes pour les opérations liées au tableau de bord
    Route::get('/getData', [DashboardController::class, 'getData']);
    Route::post('/tache/getData', [DashboardController::class, 'getDataTache']);
    Route::post('/addListe', [DashboardController::class, 'addListe']);
    Route::post('/deleteListe', [DashboardController::class, 'deleteListe']);
    Route::post('/deleteTask', [DashboardController::class, 'deleteTask']);
    Route::post('/addTask', [DashboardController::class, 'addTask']);
    Route::post('/modifTask', [DashboardController::class, 'modifTask']);
    Route::post('/shareListe', [DashboardController::class, 'shareListe']);
    Route::post('/leaveListe', [DashboardController::class, 'leaveListe']);
});

Route::middleware('auth')->prefix('myAccount')->group(function () {
    Route::get('/', [myAccountController::class, 'index']);
    Route::get('/user', [myAccountController::class, 'getProfil']);
    Route::post('/userPost', [myAccountController::class, 'post']);
    Route::post('/changeMdp', [myAccountController::class, 'changeMdp']);
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('admin:admin');
Route::get('/admin/users', [AdminController::class, 'getUsers'])->middleware('admin:admin');
Route::post('/admin/deleteUser', [AdminController::class, 'deleteUser'])->middleware('admin:admin');

