<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamSubjectScore;
use Illuminate\Support\Facades\Auth;

class StudentExamResult extends Controller
{
    public function all(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $this->validate($request, [
            'exam_id' => 'required|integer|exists:exams,id',
        ]);

        $subject_ids = $user->student->subjects->pluck('id')->toArray();
        $exam_subject_ids = ExamSubject::where('exam_id', $request->exam_id)->whereIn('subject_id', $subject_ids)->pluck('id')->toArray();
        $exam_subject_scores = ExamSubjectScore::where('user_id', $user->id)->whereIn('exam_subject_id', $exam_subject_ids)->with('examSubject', 'examSubject.subject', 'examSubjectState')->get();

        return $exam_subject_scores;
    }
}
