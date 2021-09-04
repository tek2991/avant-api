<?php

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SpaLoginController;
use App\Http\Controllers\Auth\SpaLogoutController;
use App\Http\Controllers\Auth\SpaRegisterController;
use App\Http\Controllers\API\v1\Import\StudentImportController;
use App\Http\Controllers\API\v1\Import\TeacherImportController;
use App\Http\Controllers\API\v1\Export\TemplateExportController;

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

Route::get('invoice', function () {
    $pdf = PDF::loadView('documents.fee-invoice');
    return $pdf->download('invoice.pdf');
    // return view('documents.fee-invoice');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/import', function () {
    return view('import');
})->middleware(['auth'])->name('import');

Route::get('/attribute-export', [TemplateExportController::class, 'attributeExport'])->middleware(['auth'])->name('attribute.export');

Route::get('/student-import', [StudentImportController::class, 'index'])->middleware(['auth'])->name('import.student');
Route::post('/student-import', [StudentImportController::class, 'store'])->middleware(['auth']);
Route::get('/student-template', [TemplateExportController::class, 'studentTemplate'])->middleware(['auth'])->name('student.template');

Route::get('/teacher-import', [TeacherImportController::class, 'index'])->middleware(['auth'])->name('import.teacher');
Route::post('/teacher-import', [TeacherImportController::class, 'store'])->middleware(['auth']);
Route::get('/teacher-template', [TemplateExportController::class, 'teacherTemplate'])->middleware(['auth'])->name('teacher.template');

Route::get('/phpinfo', function () {
    return view('phpinfo');
})->middleware(['auth'])->name('phpinfo');

Route::prefix('pwa/')->group(function () {
    Route::post('register', [SpaRegisterController::class, 'register'])->name('register');
    Route::post('director-login', [SpaLoginController::class, 'directorLogin'])->name('director-login');
    Route::post('logout', [SpaLogoutController::class, 'logout'])->name('logout');
});

require __DIR__.'/auth.php';
