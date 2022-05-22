<?php

namespace App\Http\Controllers\API\v1\Exam;

use PDF;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Models\User;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;

class QuestionPaperController extends Controller
{
    public function print(Request $request, ExamSubject $examSubject)
    {
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

        if (!hash_equals($pas->token, hash('sha256', $token))) {
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

        $file_name = 'Question_Paper_' . str_replace(" ", "_", $examSubject->subject->name) . '_' . str_replace(" ", "_", $examSubject->exam->name) . '_.pdf';

        // return view('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));
        // $pdf = PDF::loadView('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));
        // return $pdf->download($file_name);


        // dd(compact('exam', 'variables', 'examSubject', 'exam_questions'));
        // $pdf = PDF::loadView('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));
        // return $pdf->download($file_name);

        // return view('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'));

        // dd(base_path('resources/fonts/'));

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'margin_left'              => 10,
            'margin_right'             => 10,
            'margin_top'               => 10,
            'margin_bottom'            => 10,
            'margin_header'            => 0,
            'margin_footer'            => 0,
            'orientation'              => 'P',

            'fontDir' => array_merge($fontDirs, [
                base_path('resources/fonts/'),
            ]),

            'fontdata' => $fontData + [
                'nikosh' => [
                    'R'  => 'Nikosh.ttf',    // regular font
                ],
                'macondo' => [
                    'R'  => 'Macondo-Regular.ttf',    // regular font
                ],
            ],

            'default_font' => 'FreeSans',
        ]);

        $html = View::make('documents.question_paper', compact('exam', 'variables', 'examSubject', 'exam_questions'))->render();

        $mpdf->WriteHTML($html);

        return $mpdf->Output($file_name, 'D');
    }
}
