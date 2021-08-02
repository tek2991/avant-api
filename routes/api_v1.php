<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\API\v1\Fee\FeeController;
use App\Http\Controllers\Auth\ApiLogoutController;
use App\Http\Controllers\API\v1\Bill\BillController;
use App\Http\Controllers\API\v1\User\UserController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\API\v1\Event\EventController;
use App\Http\Controllers\API\v1\Bill\BillFeeController;
use App\Http\Controllers\API\v1\User\TeacherController;
use App\Http\Controllers\API\v1\Appeal\AppealController;
use App\Http\Controllers\API\v1\Setup\SectionController;
use App\Http\Controllers\API\v1\Setup\SessionController;
use App\Http\Controllers\API\v1\Fee\ChargeableController;
use App\Http\Controllers\API\v1\Setup\StandardController;
use App\Http\Controllers\API\v1\Subject\StreamController;
use App\Http\Controllers\API\v1\Bill\FeeInvoiceController;
use App\Http\Controllers\API\v1\Event\EventTypeController;
use App\Http\Controllers\API\v1\Student\StudentController;
use App\Http\Controllers\API\v1\Subject\ChapterController;
use App\Http\Controllers\API\v1\Subject\SubjectController;
use App\Http\Controllers\API\v1\Attributes\GenderController;
use App\Http\Controllers\API\v1\Appeal\CloseAppealController;
use App\Http\Controllers\API\v1\Subject\SubjectGroupController;
use App\Http\Controllers\API\v1\Attendance\AttendanceController;
use App\Http\Controllers\API\v1\Attributes\BloodGroupController;
use App\Http\Controllers\API\v1\Setup\SectionStandardController;
use App\Http\Controllers\API\v1\Student\EnrollStudentController;
use App\Http\Controllers\API\v1\Appeal\RecommendAppealController;
use App\Http\Controllers\API\v1\Subject\StudentSubjectController;
use App\Http\Controllers\API\v1\Subject\SubjectTeacherController;
use App\Http\Controllers\API\v1\Fee\AttachStandardToFeeController;
use App\Http\Controllers\API\v1\Fee\AttachChargeableToFeeController;
use App\Http\Controllers\API\v1\Student\UnallocatedStudentController;
use App\Http\Controllers\API\v1\Razorpay\RazorpayFeeInvoiceController;
use App\Http\Controllers\API\v1\Attendance\StudentAttendanceController;
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
    
    Route::apiResource('session', SessionController::class)->middleware(['can:session_crud']);
    Route::get('session-all', [SessionController::class, 'all'])->middleware(['can:session_read']);

    Route::apiResource('standard', StandardController::class)->middleware(['can:standard_crud']);
    Route::get('standard-all', [StandardController::class, 'all'])->middleware(['can:standard_read']);

    Route::apiResource('section', SectionController::class)->middleware(['can:section_crud']);
    Route::get('section-all', [SectionController::class, 'all'])->middleware(['can:section_read']);
    
    Route::apiResource('section-standard', SectionStandardController::class)->except(['index'])->middleware(['can:standard_crud', 'can:section_crud']);
    Route::get('section-standard', [SectionStandardController::class, 'index'])->middleware(['can:standard_read', 'can:section_read']);
    Route::get('section-standard-all', [SectionStandardController::class, 'all'])->middleware(['can:standard_read', 'can:section_read']);

    Route::apiResource('teacher', TeacherController::class)->only(['index', 'store'])->middleware(['can:teacher_crud']);
    Route::get('teacher-all', [TeacherController::class, 'all'])->middleware(['can:teacher_read']);

    Route::apiResource('gender', GenderController::class)->only(['index'])->middleware(['can:gender_read']);
    Route::apiResource('blood-group', BloodGroupController::class)->only(['index'])->middleware(['can:blood_group_read']);
    
    Route::apiResource('fee', FeeController::class)->middleware(['can:bill_crud']);
    Route::get('fee-all', [FeeController::class, 'all'])->middleware(['can:bill_read']);

    Route::apiResource('chargeable', ChargeableController::class)->middleware(['can:bill_crud']);
    Route::get('chargeable-all', [ChargeableController::class, 'all'])->middleware(['can:bill_read']);

    Route::apiResource('student', StudentController::class)->only(['index', 'store', 'update', 'destroy'])->middleware(['can:student_crud']);
    Route::get('unallocated-student', [UnallocatedStudentController::class, 'index'])->middleware(['can:student_read']);
    Route::post('enroll-student', [EnrollStudentController::class, 'store'])->middleware(['can:student_crud']);

    Route::post('attach-chargeable-to-fee', [AttachChargeableToFeeController::class, 'store'])->middleware(['can:bill_crud']);
    Route::post('attach-standard-to-fee', [AttachStandardToFeeController::class, 'store'])->middleware(['can:bill_crud']);
    Route::post('attach-student-to-chargeable', [AttachStudentToChargeableController::class, 'store'])->middleware(['can:bill_crud']);

    Route::apiResource('bill', BillController::class)->only(['index', 'store', 'show'])->middleware(['can:bill_crud']);
    Route::get('bill-all', [BillController::class, 'all'])->middleware(['can:bill_read']);
    Route::apiResource('bill-fee', BillFeeController::class)->only(['show'])->middleware(['can:bill_read']);
    Route::apiResource('fee-invoice', FeeInvoiceController::class)->only(['index', 'show'])->middleware(['can:bill_read']);

    Route::get('razorpay-fee-invoice/{fee_invoice}', [RazorpayFeeInvoiceController::class, 'show']);
    Route::post('razorpay-verify-payment/{fee_invoice}', [RazorpayFeeInvoiceController::class, 'verifyPayment']);
    
    
    Route::apiResource('appeal', AppealController::class);
    Route::post('recommend-appeal/{appeal}', [RecommendAppealController::class, 'store'])->middleware(['can:appeal_crud']);
    Route::post('close-appeal/{appeal}', [CloseAppealController::class, 'store'])->middleware(['can:appeal_crud']);
    
    Route::get('attendance/{section_standard}', [AttendanceController::class, 'show']);
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::post('attendance', [AttendanceController::class, 'store']);

    Route::get('student-attendance', [StudentAttendanceController::class, 'index']);

    Route::apiResource('event', EventController::class);
    Route::get('event-type', [EventTypeController::class, 'index']);

    Route::apiResource('stream', StreamController::class)->middleware(['can:session_crud']);
    Route::get('stream-all', [StreamController::class, 'all'])->middleware(['can:session_read']);

    Route::apiResource('subject-group', SubjectGroupController::class)->middleware(['can:session_crud']);
    Route::get('subject-group-all', [SubjectGroupController::class, 'all'])->middleware(['can:session_read']);

    Route::apiResource('subject', SubjectController::class)->middleware(['can:session_crud']);
    Route::apiResource('subject-teacher', SubjectTeacherController::class)->middleware(['can:session_crud']);
    Route::apiResource('student-subject', StudentSubjectController::class)->middleware(['can:session_crud']);
    Route::apiResource('chapter', ChapterController::class)->middleware(['can:session_crud']);
    
    Route::post('logout', [ApiLogoutController::class, 'logout'])->name('api-logout');
});

Route::get('fee-invoice-print/{fee_invoice}', [FeeInvoiceController::class, 'print']);
Route::get('fee-invoice-receipt/{fee_invoice}', [FeeInvoiceController::class, 'printReceipt']);

// Route::post('/razorpay-callback', function () {
//     return response('OK', 200);
// });

Route::post('razorpay-webhook', [RazorpayFeeInvoiceController::class, 'webhook']);

Route::get('register', [ApiRegisterController::class, 'api-register']);

Route::post('director-login', [ApiLoginController::class, 'directorLogin'])->name('api-director-login');
Route::post('student-login', [ApiLoginController::class, 'studentLogin'])->name('api-student-login');
Route::post('teacher-login', [ApiLoginController::class, 'teacherLogin'])->name('api-teacher-login');
