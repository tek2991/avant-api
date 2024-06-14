<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use App\Models\ExamSubjectScore;
use Illuminate\Support\Facades\Auth;

class EvaluateAnswerController extends Controller
{
    public function update(ExamAnswer $examAnswer, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true || $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'marks_secured' => 'bail|required|numeric',
            'exam_subject_id' => 'bail|required|numeric|exists:exam_subject,id',
            'user_id' => 'bail|required|numeric|exists:users,id',
        ]);

        if ($examAnswer->examQuestion->examQuestionType->name != 'Descriptive') {
            return response([
                'header' => 'Forbidden',
                'message' => 'Only Descriptive type questions can be evaluated.'
            ], 403);
        }

        if ($request->marks_secured > $examAnswer->examQuestion->marks) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Marks secured cannot be greater than marks allotted.'
            ], 403);
        }

        if ($examAnswer->examQuestion->examSubject->examSubjectState->name != 'Evaluating') {
            return response([
                'header' => 'Forbidden',
                'message' => 'Exam is not in evaluation state.'
            ], 403);
        }

        $examAnswer->update([
            'marks_secured' => $request->marks_secured,
            'evaluated_by' => $user->id,
        ]);

        $exam_question_ids = ExamQuestion::where('exam_subject_id', $request->exam_subject_id)->pluck('id');
        $total_makrs_secured = ExamAnswer::where('user_id', $request->user_id)->whereIn('exam_question_id', $exam_question_ids)->sum('marks_secured');
        $examSubjectScore = ExamSubjectScore::where('exam_subject_id', $request->exam_subject_id)->where('user_id', $request->user_id)->first();
        $examSubjectScore->update([
            'marks_secured' => $total_makrs_secured,
            'evaluated_by' => $user->id,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Answer evaluated successfully.'
        ], 200);
    }
}
