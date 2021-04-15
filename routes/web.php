<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SpaLoginController;
use App\Http\Controllers\Auth\SpaLogoutController;
use App\Http\Controllers\Auth\SpaRegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('register', [SpaRegisterController::class, 'register'])->name('register');
Route::post('director-login', [SpaLoginController::class, 'directorLogin'])->name('director-login');
Route::post('logout', [SpaLogoutController::class, 'logout'])->name('logout');
