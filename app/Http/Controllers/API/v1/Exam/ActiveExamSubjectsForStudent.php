<?php

namespace App\Http\Controllers\API\v1\Exam;

use Auth;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Models\ExamSubjectState;
use App\Http\Controllers\Controller;

class ActiveExamSubjectsForStudent extends Controller
{
    public function index(){
        $user = Auth::user();
        if($user->hasRole('student') !== true){
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $student_subject_ids = $user->student->subjects->pluck('id')->toArray();
        $exam_subject_active_state_id = ExamSubjectState::where('name', 'Active')->first()->id;

        $exam_subjects = ExamSubject::whereIn('subject_id', $student_subject_ids)->where('exam_subject_state_id', $exam_subject_active_state_id)->with('subject.standard', 'examSchedule', 'exam', 'examSubjectState', 'examQuestions:exam_subject_id,id,marks')->whereHas('examSchedule')->orderBy('exam_schedule_id')->get();

        return $exam_subjects;
    }
}
