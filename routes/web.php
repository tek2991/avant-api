<?php

use Barryvdh\DomPDF\Facade as PDF;
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

Route::post('/razorpay-callback', function () {
    return response('OK', 200);
});

Route::get('invoice', function () {
    $pdf = PDF::loadView('documents.fee-invoice');
    return $pdf->download('invoice.pdf');
    // return view('documents.fee-invoice');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::prefix('pwa/')->group(function () {
    Route::post('register', [SpaRegisterController::class, 'register'])->name('register');
    Route::post('director-login', [SpaLoginController::class, 'directorLogin'])->name('director-login');
    Route::post('logout', [SpaLogoutController::class, 'logout'])->name('logout');
});

require __DIR__.'/auth.php';
