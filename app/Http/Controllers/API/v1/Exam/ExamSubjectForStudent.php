<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSubjectForStudent extends Controller
{
    public static function index(Exam $exam, $userModel = null)
    {
        $user = $userModel ? $userModel : Auth::user();
        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $student_subject_ids = $user->student->subjects->pluck('id')->toArray();

        $exam_subjects = ExamSubject::where('exam_subject.exam_id', $exam->id)
        ->whereIn('exam_subject.subject_id', $student_subject_ids)
        ->leftJoin('exam_schedules', 'exam_subject.exam_schedule_id', '=', 'exam_schedules.id')
        ->select('exam_subject.*', 'exam_schedules.start')
        ->with('subject.standard', 'examSchedule')
        ->orderBy('exam_schedules.start')
        ->get();

        return $exam_subjects;
    }
}
