<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
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

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware('auth');
Route::get('/dashboard/getData', [DashboardController::class, 'getData'])->middleware('auth');
Route::post('/dashboard/tache/getData', [DashboardController::class, 'getDataTache'])->middleware('auth');
Route::post('/dashboard/addListe', [DashboardController::class, 'addListe'])->middleware('auth');
Route::post('/dashboard/deleteListe', [DashboardController::class, 'deleteListe'])->middleware('auth');
Route::post('/dashboard/deleteTask', [DashboardController::class, 'deleteTask'])->middleware('auth');
Route::post('/dashboard/addTask', [DashboardController::class, 'addTask'])->middleware('auth');
Route::post('/dashboard/modifTask', [DashboardController::class, 'modifTask'])->middleware('auth');
Route::post('/dashboard/shareListe', [DashboardController::class, 'shareListe'])->middleware('auth');
Route::post('/dashboard/leaveListe', [DashboardController::class, 'leaveListe'])->middleware('auth');

