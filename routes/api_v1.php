<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\SMS\SendSms;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\API\v1\Fee\FeeController;
use App\Http\Controllers\Auth\ApiLogoutController;
use App\Http\Controllers\API\v1\Bill\BillController;
use App\Http\Controllers\API\v1\Exam\ExamController;
use App\Http\Controllers\API\v1\User\UserController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\API\v1\Event\EventController;
use App\Http\Controllers\API\v1\Bill\BillFeeController;
use App\Http\Controllers\API\v1\User\TeacherController;
use App\Http\Controllers\API\v1\Appeal\AppealController;
use App\Http\Controllers\API\v1\Exam\ExamTypeController;
use App\Http\Controllers\API\v1\Exam\ExamUserController;
use App\Http\Controllers\API\v1\Export\ExportController;
use App\Http\Controllers\API\v1\Setup\SectionController;
use App\Http\Controllers\API\v1\Setup\SessionController;
use App\Http\Controllers\API\v1\SMS\SmsRecordController;
use App\Http\Controllers\API\v1\Exam\ExamUserForStudents;
use App\Http\Controllers\API\v1\Fee\ChargeableController;
use App\Http\Controllers\API\v1\Setup\StandardController;
use App\Http\Controllers\API\v1\Subject\StreamController;
use App\Http\Controllers\API\v1\Attributes\BankController;
use App\Http\Controllers\API\v1\Bill\FeeInvoiceController;
use App\Http\Controllers\API\v1\Event\EventTypeController;
use App\Http\Controllers\API\v1\Exam\ExamAnswerController;
use App\Http\Controllers\API\v1\Student\StudentController;
use App\Http\Controllers\API\v1\Subject\ChapterController;
use App\Http\Controllers\API\v1\Subject\SubjectController;
use App\Http\Controllers\API\v1\Attributes\CasteController;
use App\Http\Controllers\API\v1\Exam\ExamSubjectController;
use App\Http\Controllers\API\v1\Attributes\GenderController;
use App\Http\Controllers\API\v1\Exam\ExamQuestionController;
use App\Http\Controllers\API\v1\Exam\ExamScheduleController;
use App\Http\Controllers\API\v1\Homework\HomeworkController;
use App\Http\Controllers\API\v1\Chart\StudentChartController;
use App\Http\Controllers\API\v1\Chart\TeacherChartController;
use App\Http\Controllers\API\v1\SMS\StudentBySectionStandard;
use App\Http\Controllers\API\v1\Attributes\LanguageController;
use App\Http\Controllers\API\v1\Attributes\ReligionController;
use App\Http\Controllers\API\v1\Chart\DirectorChartController;
use App\Http\Controllers\API\v1\Appeal\ResolveAppealController;
use App\Http\Controllers\API\v1\Exam\ExamAnswerStateController;
use App\Http\Controllers\API\v1\Subject\SubjectGroupController;
use App\Http\Controllers\API\v1\Attendance\AttendanceController;
use App\Http\Controllers\API\v1\Attributes\AppealTypeController;
use App\Http\Controllers\API\v1\Attributes\BloodGroupController;
use App\Http\Controllers\API\v1\Attributes\InstrumentController;
use App\Http\Controllers\API\v1\Exam\ExamQuestionTypeController;
use App\Http\Controllers\API\v1\Fee\ChargeableStudentController;
use App\Http\Controllers\API\v1\Setup\SectionStandardController;
use App\Http\Controllers\API\v1\Student\EnrollStudentController;
use App\Http\Controllers\API\v1\Student\UpdateStudentController;
use App\Http\Controllers\API\v1\Student\StudentTrashedController;
use App\Http\Controllers\API\v1\Subject\StudentSubjectController;
use App\Http\Controllers\API\v1\Subject\SubjectTeacherController;
use App\Http\Controllers\API\v1\Exam\ActiveExamSubjectsForStudent;
use App\Http\Controllers\API\v1\Fee\AttachStandardToFeeController;
use App\Http\Controllers\API\v1\Student\TransferStudentController;
use App\Http\Controllers\API\v1\Exam\ExamSectionStandardController;
use App\Http\Controllers\API\v1\Fee\AttachChargeableToFeeController;
use App\Http\Controllers\API\v1\Notification\NotificationController;
use App\Http\Controllers\API\v1\Subject\SubjectForStudentController;
use App\Http\Controllers\API\v1\Subject\SubjectForTeacherController;
use App\Http\Controllers\API\v1\Student\UnallocatedStudentController;
use App\Http\Controllers\API\v1\Subject\ChapterProgressionController;
use App\Http\Controllers\API\v1\TinyMce\TinyMceImageUploadController;
use App\Http\Controllers\API\v1\ManualPayment\ManualPaymentController;
use App\Http\Controllers\API\v1\Razorpay\RazorpayFeeInvoiceController;
use App\Http\Controllers\API\v1\Attendance\StudentAttendanceController;
use App\Http\Controllers\API\v1\Exam\AdmitCardController;
use App\Http\Controllers\API\v1\Notification\NotificationTypeController;
use App\Http\Controllers\API\v1\SMS\StudentBySectionStandardWithUnpaidDue;
use App\Http\Controllers\API\v1\SMS\StudentBySectionStandardWithAttendance;

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

    Route::apiResource('gender', GenderController::class)->only(['index']);
    Route::apiResource('language', LanguageController::class)->only(['index']);
    Route::apiResource('religion', ReligionController::class)->only(['index']);
    Route::apiResource('caste', CasteController::class)->only(['index']);
    Route::apiResource('blood-group', BloodGroupController::class)->only(['index']);
    Route::apiResource('bank', BankController::class)->only(['index']);
    Route::apiResource('instrument', InstrumentController::class)->only(['index']);
    Route::apiResource('appeal-type', AppealTypeController::class)->only(['index']);
    Route::apiResource('exam-type', ExamTypeController::class)->only(['index']);
    Route::get('exam-question-type', [ExamQuestionTypeController::class, 'index']);
    
    Route::apiResource('fee', FeeController::class)->middleware(['can:bill_crud']);
    Route::get('fee-all', [FeeController::class, 'all'])->middleware(['can:bill_read']);

    Route::apiResource('chargeable', ChargeableController::class)->middleware(['can:bill_crud']);
    Route::get('chargeable-all', [ChargeableController::class, 'all'])->middleware(['can:bill_read']);

    Route::apiResource('student', StudentController::class)->only(['index', 'update', 'destroy'])->middleware(['can:student_crud']);
    Route::apiResource('student-trashed', StudentTrashedController::class)->only(['index', 'store'])->middleware(['can:student_crud']);
    Route::put('student-trashed/{user}', [StudentTrashedController::class, 'update'])->middleware(['can:student_crud']);

    Route::get('unallocated-student', [UnallocatedStudentController::class, 'index'])->middleware(['can:student_read']);
    Route::post('enroll-student', [EnrollStudentController::class, 'store'])->middleware(['can:student_crud']);
    Route::put('update-student/{user}', [UpdateStudentController::class, 'update'])->middleware(['can:student_crud']);
    
    Route::post('attach-chargeable-to-fee', [AttachChargeableToFeeController::class, 'store'])->middleware(['can:bill_crud']);
    Route::post('attach-standard-to-fee', [AttachStandardToFeeController::class, 'store'])->middleware(['can:bill_crud']);
    Route::apiResource('chargeable-student', ChargeableStudentController::class)->middleware(['can:bill_crud']);
    Route::get('student-by-standard/{standard}', [StandardController::class, 'studentByStandard'])->middleware(['can:student_read']);
    Route::get('students-by-section-standard', [TransferStudentController::class, 'studentBySectionStandard'])->middleware(['can:student_read']);
    Route::put('transfer-student', [TransferStudentController::class, 'transferStudent'])->middleware(['can:student_crud']);

    Route::apiResource('bill', BillController::class)->only(['index', 'store', 'show'])->middleware(['can:bill_crud']);
    Route::get('bill-all', [BillController::class, 'all'])->middleware(['can:bill_read']);
    Route::apiResource('bill-fee', BillFeeController::class)->only(['show'])->middleware(['can:bill_read']);
    Route::apiResource('fee-invoice', FeeInvoiceController::class)->only(['index', 'show'])->middleware(['can:bill_read']);

    Route::get('razorpay-fee-invoice/{fee_invoice}', [RazorpayFeeInvoiceController::class, 'show']);
    Route::post('razorpay-verify-payment/{fee_invoice}', [RazorpayFeeInvoiceController::class, 'verifyPayment']);
    
    Route::get('manual-payment/{fee_invoice}', [ManualPaymentController::class, 'show'])->middleware(['can:manual_payment_crud']);
    Route::put('manual-payment/{manual_payment}', [ManualPaymentController::class, 'update'])->middleware(['can:manual_payment_crud']);
    
    Route::apiResource('appeal', AppealController::class);
    Route::put('appeal-resolve/{appeal}', [ResolveAppealController::class, 'store'])->middleware(['can:appeal_crud']);
    
    Route::get('attendance/{section_standard}', [AttendanceController::class, 'show']);
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::post('attendance', [AttendanceController::class, 'store']);

    Route::get('student-attendance', [StudentAttendanceController::class, 'index']);

    Route::get('student-attendance-for-session/{user}', [StudentChartController::class, 'attendanceForSession']);
    Route::get('all-user-invoice/{user}', [StudentChartController::class, 'allUserInvoices']);
    Route::get('all-user-chapter-in-progress/{user}', [StudentChartController::class, 'allChaptersInProgress']);

    Route::get('all-invoice-stat', [DirectorChartController::class, 'allInvoiceStat']);
    Route::get('all-attendance-record', [DirectorChartController::class, 'allAttendanceRecord']);

    Route::get('attendance-for-assigned-class/{user}', [TeacherChartController::class, 'attendanceForAssignedClasses']);
    Route::get('assigned-user-chapter-in-progress/{user}', [TeacherChartController::class, 'assignedChaptersInProgress']);

    Route::get('homework', [HomeworkController::class, 'index'])->middleware(['can:homework_read']);
    Route::post('homework', [HomeworkController::class, 'store'])->middleware(['can:homework_crud']);
    Route::put('homework/{homework}', [HomeworkController::class, 'update'])->middleware(['can:homework_crud']);
    Route::delete('homework/{homework}', [HomeworkController::class, 'destroy'])->middleware(['can:homework_crud']);

    Route::apiResource('event', EventController::class);
    Route::get('event-type', [EventTypeController::class, 'index']);

    Route::apiResource('stream', StreamController::class)->middleware(['can:subject_crud']);
    Route::get('stream-all', [StreamController::class, 'all'])->middleware(['can:subject_read']);

    Route::apiResource('subject-group', SubjectGroupController::class)->middleware(['can:subject_crud']);
    Route::get('subject-group-all', [SubjectGroupController::class, 'all'])->middleware(['can:subject_read']);

    Route::apiResource('subject', SubjectController::class)->middleware(['can:subject_crud']);
    Route::get('subject-all', [SubjectController::class, 'all'])->middleware(['can:subject_read']);
    Route::get('subject-for-teacher/{user}', [SubjectForTeacherController::class, 'index'])->middleware(['can:subject_read']);
    Route::get('teacher-subject-by-section-standard/{section_standard}', [SubjectForTeacherController::class, 'teacherSubjectBySectionStandard'])->middleware(['can:subject_read']);
    Route::get('subject-for-student/{user}', [SubjectForStudentController::class, 'index'])->middleware(['can:subject_read']);
    
    Route::apiResource('subject-teacher', SubjectTeacherController::class)->middleware(['can:subject_crud']);

    Route::apiResource('student-subject', StudentSubjectController::class)->except(['index'])->middleware(['can:subject_crud']);
    Route::get('student-subject', [StudentSubjectController::class, 'index'])->middleware(['can:subject_read']);
    
    Route::get('subject-for-teacher', [StudentSubjectController::class, 'index'])->middleware(['can:subject_read']);

    Route::apiResource('chapter', ChapterController::class)->except(['index'])->middleware(['can:subject_crud']);
    Route::get('chapter', [ChapterController::class, 'index'])->middleware(['can:subject_read']);
    Route::get('chapter-all/{subject}', [ChapterController::class, 'all'])->middleware(['can:subject_read']);

    Route::apiResource('chapter-progression', ChapterProgressionController::class);

    Route::get('student-by-section-standard', [StudentBySectionStandard::class, 'index']);
    Route::get('student-by-section-standard-with-attendance', [StudentBySectionStandardWithAttendance::class, 'index']);
    Route::get('student-by-section-standard-with-unpaid-due', [StudentBySectionStandardWithUnpaidDue::class, 'index']);
    Route::post('send-holiday-sms', [SendSms::class, 'holidaySms']);
    Route::post('send-absentee-sms', [SendSms::class, 'absenteeSms']);
    Route::post('send-unpaid-due-sms', [SendSms::class, 'unpaidDueSms']);

    Route::get('notification-type', [NotificationTypeController::class, 'index']);

    Route::get('exam', [ExamController::class, 'index']);
    Route::get('exam/{exam}', [ExamController::class, 'show']);
    Route::post('exam', [ExamController::class, 'store']);

    Route::get('admit-card/{exam}', [AdmitCardController::class, 'show']);

    Route::get('exam-schedule/{exam}', [ExamScheduleController::class, 'index']);
    Route::get('exam-schedule-all/{exam}', [ExamScheduleController::class, 'all']);
    Route::put('exam-schedule/{exam_schedule}', [ExamScheduleController::class, 'update']);
    Route::post('exam-schedule', [ExamScheduleController::class, 'store']);
    Route::delete('exam-schedule/{exam_schedule}', [ExamScheduleController::class, 'destroy']);
    Route::post('exam-schedule-control/{exam_schedule}', [ExamScheduleController::class, 'control']);

    Route::get('exam-user/{exam}', [ExamUserController::class, 'index']);
    Route::put('exam-user/{exam}', [ExamUserController::class, 'update']);
    Route::get('exam-user-all-student', [ExamUserForStudents::class, 'all']);

    Route::get('exam-answer-all/{exam_subject}', [ExamAnswerController::class, 'all']);
    Route::post('exam-answer', [ExamAnswerController::class, 'store']);
    Route::put('exam-answer/{exam_answer}', [ExamAnswerController::class, 'update']);

    Route::get('exam-answer-state', [ExamAnswerStateController::class, 'index']);

    Route::get('exam-section-standard/{exam}', [ExamSectionStandardController::class, 'index']);
    Route::post('exam-section-standard', [ExamSectionStandardController::class, 'store']);
    Route::delete('exam-section-standard/{exam_section_standard}', [ExamSectionStandardController::class, 'destroy']);

    Route::get('exam-subject/{exam}', [ExamSubjectController::class, 'index']);
    Route::get('exam-subject-show/{exam_subject}', [ExamSubjectController::class, 'show']);
    Route::get('exam-subject-all/{exam}', [ExamSubjectController::class, 'allExcluded']);
    Route::put('exam-subject/{exam_subject}', [ExamSubjectController::class, 'update']);
    Route::post('exam-subject', [ExamSubjectController::class, 'store']);
    Route::delete('exam-subject/{exam_subject}', [ExamSubjectController::class, 'destroy']);

    Route::get('active-exam-subjects-for-student', [ActiveExamSubjectsForStudent::class, 'index']);

    Route::get('exam-question/{exam_subject}', [ExamQuestionController::class, 'index']);
    Route::get('exam-question-show/{exam_question}', [ExamQuestionController::class, 'show']);
    Route::post('exam-question', [ExamQuestionController::class, 'store']);
    Route::put('exam-question/{exam_question}', [ExamQuestionController::class, 'update']);
    Route::delete('exam-question/{exam_question}', [ExamQuestionController::class, 'destroy']);

    Route::post('tiny_mce_img_upload', [TinyMceImageUploadController::class, 'store']);
    
    Route::get('notification', [NotificationController::class, 'index']);
    Route::post('notification', [NotificationController::class, 'store']);
    Route::delete('notification/{notification}', [NotificationController::class, 'destroy']);
    
    Route::post('logout', [ApiLogoutController::class, 'logout'])->name('api-logout');
});

Route::get('fee-invoice-print/{fee_invoice}', [FeeInvoiceController::class, 'print']);
Route::get('fee-invoice-receipt/{fee_invoice}', [FeeInvoiceController::class, 'printReceipt']);

Route::get('bill-fee-export/{bill_fee}', [ExportController::class, 'billFee']);
Route::get('sms-report', [SmsRecordController::class, 'index']);

// Route::post('/razorpay-callback', function () {
//     return response('OK', 200);
// });

Route::post('razorpay-webhook', [RazorpayFeeInvoiceController::class, 'webhook']);

Route::get('register', [ApiRegisterController::class, 'api-register']);

Route::post('director-login', [ApiLoginController::class, 'directorLogin'])->name('api-director-login');
Route::post('student-login', [ApiLoginController::class, 'studentLogin'])->name('api-student-login');
Route::post('teacher-login', [ApiLoginController::class, 'teacherLogin'])->name('api-teacher-login');
