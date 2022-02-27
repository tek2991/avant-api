<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        if($examAnswer->examQuestion->examSubject->examSubjectState->name != 'Evaluating') {
            return response([
                'header' => 'Forbidden',
                'message' => 'Exam is not in evaluation state.'
            ], 403);
        }

        $examAnswer->update([
            'marks_secured' => $request->marks_secured,
            'evaluated_by' => $user->id,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Answer evaluated successfully.'
        ], 200);
    }
}
