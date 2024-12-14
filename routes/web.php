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
Route::get('/cgu', function () {
    return view('home.cgu');
});
Route::get('/register', function () {
    return view('users.register');
});
Route::get('/login', function () {
    return view('users.login');
});

Route::post('/register/adduser', [RegisterController::class, 'addUser']);
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/verif', [LoginController::class, 'verif'])->name('verif');
Route::post('/resendCode', [LoginController::class, 'resendCode'])->name('resendCode');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('password/sendCode', [LoginController::class, 'sendCodeMdp']);
Route::post('password/verifyCode', [LoginController::class, 'verifyCodeMdp']);
Route::post('password/change', [LoginController::class, 'changeCodeMdp']);

Route::middleware('web-auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard.dashboard'); });
});


Route::middleware('auth')->prefix('dashboard')->group(function () {
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
    Route::post('/putListe' , [DashboardController::class, 'putListe']);
    Route::post('/modifDataTask', [DashboardController::class, 'modifDataTask']);
});

Route::middleware('auth')->prefix('myAccount')->group(function () {
    Route::get('/', [myAccountController::class, 'index']);
    Route::get('/user', [myAccountController::class, 'getProfil']);
    Route::post('/userPost', [myAccountController::class, 'post']);
    Route::post('/changeMdp', [myAccountController::class, 'changeMdp']);
    Route::post('/delete', [myAccountController::class, 'deleteAccount']);
});
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->middleware('admin:admin');
    Route::get('/users', [AdminController::class, 'getUsers'])->middleware('admin:admin');
    Route::post('/deleteUser', [AdminController::class, 'deleteUser'])->middleware('admin:admin');
    Route::post('/modifUser', [AdminController::class, 'modifUser'])->middleware('admin:admin');
    Route::post('/deletePermissionRole', [AdminController::class, 'deletePermissionRole'])->middleware('admin:admin');
    Route::post('/updateRole', [AdminController::class, 'addPermissionRole'])->middleware('admin:admin');
    Route::post('/createRole', [AdminController::class, 'addRole'])->middleware('admin:admin');
    Route::post('/deleteRole', [AdminController::class, 'deleteRole'])->middleware('admin:admin');
    Route::post('/createPermission', [AdminController::class, 'addPermission'])->middleware('admin:admin');
    Route::post('/deletePermission', [AdminController::class, 'deletePermission'])->middleware('admin:admin');
    Route::post('/addUser', [AdminController::class, 'addUser'])->middleware('admin:admin');
});

