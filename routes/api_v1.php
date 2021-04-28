<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Auth\ApiLogoutController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\API\v1\Setup\SessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('user', UserController::class)->except([
        'show', 'destroy'
    ]);
    Route::get('user/{user?}', [UserController::class, 'show'])->name('user.show');
    
    Route::apiResource('session', SessionController::class);

    Route::post('logout', [ApiLogoutController::class, 'logout'])->name('api-logout');
});

Route::get('register', [ApiRegisterController::class, 'api-register']);
Route::post('director-login', [ApiLoginController::class, 'directorLogin'])->name('api-director-login');
