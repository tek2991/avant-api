<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Auth\ApiLogoutController;
use App\Http\Controllers\Auth\ApiRegisterController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum', 'can:view profile'])->group(function () {
    Route::get('/user', function (Request $request) {
        // Uses first & second middleware...
        return $request->user();
    });

    Route::post('logout', [ApiLogoutController::class, 'logout'])->name('api-logout');
});

Route::get('register', [ApiRegisterController::class, 'api-register']);

Route::post('director-login', [ApiLoginController::class, 'directorLogin'])->name('api-director-login');

