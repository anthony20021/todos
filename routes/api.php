<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\myAccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register/adduser', [RegisterController::class, 'addUser']);
Route::post('/login', [LoginController::class, 'authenticate']);
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
    Route::post('/putListe' , [DashboardController::class, 'putListe']);
    Route::post('/modifDataTask', [DashboardController::class, 'modifDataTask']);
});

Route::middleware('auth')->prefix('myAccount')->group(function () {
    Route::get('/', [myAccountController::class, 'index']);
    Route::get('/user', [myAccountController::class, 'getProfil']);
    Route::post('/userPost', [myAccountController::class, 'post']);
    Route::post('/changeMdp', [myAccountController::class, 'changeMdp']);
});
