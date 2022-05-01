<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;

class QuestionPaperController extends Controller
{
    public function print(Request $request, ExamSubject $examSubject){
        if (strpos($request->pat, '|') === false) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $patArray = explode("|", $request->pat);
        $model_id = $patArray[0];
        $token = $patArray[1];
        $pas = PersonalAccessToken::findOrFail($model_id);

        if(!hash_equals($pas->token, hash('sha256', $token))){
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('teacher') !== true && $user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again'
            ], 401);
        }

        $exam = $examSubject->exam;
        $exam_questions = $examSubject->examQuestions()->with('examQuestionOptions', 'examQuestionType')->orderBy('id')->get();

        $variables = VariableController::keyPairs();

        $file_name = 'Admit_Card_'. str_replace(" ","_", $examSubject->subject->name) . '_'. str_replace(" ","_", $examSubject->exam->name) . '_.pdf';

        // return view('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));
        $pdf = PDF::loadView('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));
        return $pdf->download($file_name);
    }
}
