<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\User;
use App\Models\Subject;
use App\Models\ExamUser;
use App\Models\Standard;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Exports\ClassExamResult;
use App\Models\ExamSubjectScore;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Exam\ExamSubjectForStudent;
use App\Http\Controllers\API\v1\Attributes\VariableController;

class DownloadResultController extends Controller
{
    public function standard(Request $request)
    {
        $this->validate($request, [
            'pat' => 'required|string',
            'exam_id' => 'required|integer|exists:exams,id',
            'standard_id' => 'required|integer|exists:standards,id',
        ]);
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

        $exam = Exam::find($request->exam_id);
        $standard = Standard::find($request->standard_id);

        $subject_ids = Subject::where('standard_id', $standard->id)->pluck('id')->toArray();
        $exam_subject_ids = ExamSubject::where('exam_id', $exam->id)->whereIn('subject_id', $subject_ids)->pluck('id')->toArray();

        $name = $exam->name . '_class_' . $standard->name . '.xlsx';
        return Excel::download(new ClassExamResult($exam_subject_ids, $request->exam_id), $name);
    }

    public function student(Request $request)
    {
        $this->validate($request, [
            'pat' => 'required|string',
            'exam_id' => 'required|integer|exists:exams,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);
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
        $hide_marks = $user->hasRole('student') === true; 

        if ($user->hasRole('student') === true && $user->id != $request->user_id) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again'
            ], 401);
        }

        if($user->hasRole('director') === true || $user->hasRole('teacher') === true) {
            $user = User::find($request->user_id);
        }

        $exam = Exam::find($request->exam_id);
        $exam_user = ExamUser::where('user_id', $user->id)->where('exam_id', $exam->id)->first();
        $variables = VariableController::keyPairs();
        $exam_subjects = ExamSubjectForStudent::index($exam, $user);
        $exam_subject_ids = $exam_subjects->pluck('id')->toArray();
        $exam_subject_scores = ExamSubjectScore::where('user_id', $user->id)->whereIn('exam_subject_id', $exam_subject_ids)->get()->keyBy('exam_subject_id');
        
        // dd($exam_subject_scores, $exam_subjects);

        $file_name = 'Admit_Card_'. str_replace(" ","_", $exam->name) . '_'. str_replace(" ","_", $exam->session->name) . '_' . str_replace(" ","_", $user->userDetail->name) . '.pdf';
        $pdf = PDF::loadView('documents.exam-result', compact('exam', 'exam_user', 'variables', 'exam_subjects', 'exam_subject_scores', 'hide_marks'));
        // return $pdf->stream();
        return $pdf->download($file_name);

        // return view('documents.exam-result', compact('exam', 'exam_user', 'variables', 'exam_subjects', 'exam_subject_scores', 'hide_marks'));
    }
}
