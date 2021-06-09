<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\User\UserController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\API\v1\Fee\FeeController;
use App\Http\Controllers\Auth\ApiLogoutController;
use App\Http\Controllers\API\v1\Bill\BillController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\API\v1\Bill\BillFeeController;
use App\Http\Controllers\API\v1\User\TeacherController;
use App\Http\Controllers\API\v1\Appeal\AppealController;
use App\Http\Controllers\API\v1\Setup\SectionController;
use App\Http\Controllers\API\v1\Setup\SessionController;
use App\Http\Controllers\API\v1\Fee\ChargeableController;
use App\Http\Controllers\API\v1\Setup\StandardController;
use App\Http\Controllers\API\v1\Bill\FeeInvoiceController;
use App\Http\Controllers\API\v1\Student\StudentController;
use App\Http\Controllers\API\v1\Attributes\GenderController;
use App\Http\Controllers\API\v1\Appeal\CloseAppealController;
use App\Http\Controllers\API\v1\Attributes\BloodGroupController;
use App\Http\Controllers\API\v1\Setup\SectionStandardController;
use App\Http\Controllers\API\v1\Appeal\RecommendAppealController;
use App\Http\Controllers\API\v1\Fee\AttachStandardToFeeController;
use App\Http\Controllers\API\v1\Fee\AttachChargeableToFeeController;
use App\Http\Controllers\API\v1\Student\UnallocatedStudentController;
use App\Http\Controllers\API\v1\Fee\AttachStudentToChargeableController;

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
    
    Route::apiResource('session', SessionController::class)->middleware(['can:session CRUD']);
    Route::get('standard-all', [StandardController::class, 'all'])->middleware(['can:standard CRUD']);
    Route::apiResource('standard', StandardController::class)->middleware(['can:standard CRUD']);
    Route::get('section-all', [SectionController::class, 'all'])->middleware(['can:section CRUD']);
    Route::apiResource('section', SectionController::class)->middleware(['can:section CRUD']);
    Route::apiResource('section-standard', SectionStandardController::class)->middleware(['can:section CRUD', 'can:standard CRUD']);

    Route::get('teacher-all', [TeacherController::class, 'all'])->middleware(['can:section CRUD']);

    Route::apiResource('gender', GenderController::class)->only(['index'])->middleware(['can:section CRUD']);
    Route::apiResource('blood-group', BloodGroupController::class)->only(['index'])->middleware(['can:section CRUD']);
    
    Route::apiResource('fee', FeeController::class)->middleware(['can:section CRUD']);
    Route::apiResource('chargeable', ChargeableController::class)->middleware(['can:section CRUD']);
    Route::apiResource('student', StudentController::class)->only(['store', 'update', 'destroy'])->middleware(['can:section CRUD']);
    Route::get('unallocated-student', [UnallocatedStudentController::class, 'index'])->middleware(['can:section CRUD']);

    Route::post('attach-chargeable-to-fee', [AttachChargeableToFeeController::class, 'store'])->middleware(['can:section CRUD']);
    Route::post('attach-standard-to-fee', [AttachStandardToFeeController::class, 'store'])->middleware(['can:section CRUD']);
    Route::post('attach-student-to-chargeable', [AttachStudentToChargeableController::class, 'store'])->middleware(['can:section CRUD']);

    Route::apiResource('bill', BillController::class)->only(['index', 'store', 'show'])->middleware(['can:section CRUD']);
    Route::apiResource('bill-fee', BillFeeController::class)->only(['show'])->middleware(['can:section CRUD']);
    Route::apiResource('fee-invoice', FeeInvoiceController::class)->only(['index', 'show'])->middleware(['can:section CRUD']);

    Route::apiResource('appeal', AppealController::class);
    Route::post('recommend-appeal/{appeal}', [RecommendAppealController::class, 'store'])->middleware(['can:section CRUD']);
    Route::post('close-appeal/{appeal}', [CloseAppealController::class, 'store'])->middleware(['can:section CRUD']);


    Route::post('logout', [ApiLogoutController::class, 'logout'])->name('api-logout');
});

Route::get('register', [ApiRegisterController::class, 'api-register']);
Route::post('director-login', [ApiLoginController::class, 'directorLogin'])->name('api-director-login');
