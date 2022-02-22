<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSubjectForStudent extends Controller
{
    public function index(Exam $exam){
        $user = Auth::user();
        if($user->hasRole('student') !== true){
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $student_subject_ids = $user->student->subjects->pluck('id')->toArray();

        $exam_subjects = ExamSubject::where('exam_id', $exam->id)->whereIn('subject_id', $student_subject_ids)->with('subject.standard', 'examSchedule')->orderBy('exam_schedule_id')->get();

        return $exam_subjects;
    }
}
