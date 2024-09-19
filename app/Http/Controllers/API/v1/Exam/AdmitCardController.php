<?php

namespace App\Http\Controllers\API\v1\Exam;

use Auth;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamType;
use App\Models\ExamUser;
use Illuminate\Http\Request;
use App\Models\ExamUserState;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;
use App\Models\SectionStandard;

class AdmitCardController extends Controller
{
    public function print(Request $request, Exam $exam)
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
        $user_id = array_key_exists(2, $patArray) ? $patArray[2] : null;
        $pas = PersonalAccessToken::findOrFail($model_id);

        if (!hash_equals($pas->token, hash('sha256', $token))) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('student') !== true && $user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again'
            ], 401);
        }

        if ($user->hasRole('director') === true) {
            $user = User::find($user_id);
        }

        $allowed_exam_user_states = ['Active', 'Provisional', 'Completed'];
        $allowed_exam_user_state_ids = ExamUserState::whereIn('name', $allowed_exam_user_states)->pluck('id')->toArray();
        $offline_exam_type_id = ExamType::where('name', 'Offline')->first()->id;
        $exam_user = ExamUser::where('user_id', $user->id)->where('exam_id', $exam->id)->first();

        if ($exam->exam_type_id != $offline_exam_type_id) {
            return response([
                'header' => 'Invalid Request',
                'message' => 'Admit Card is issued only for offline exams'
            ], 401);
        }
        if (!in_array($exam_user->exam_user_state_id, $allowed_exam_user_state_ids)) {
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $variables = VariableController::keyPairs();
        $exam_subjects = ExamSubjectForStudent::index($exam, $user);
        $file_name = 'Admit_Card_' . str_replace(" ", "_", $exam->name) . '_' . str_replace(" ", "_", $exam->session->name) . '_' . $user->student->sectionStandard->standard->name . '_' . str_replace(" ", "_", $user->userDetail->name) . '.pdf';

        $pdf = PDF::loadView('documents.admit-card', compact('exam', 'exam_user', 'variables', 'exam_subjects'));
        return $pdf->download($file_name);

        // return view('documents.admit-card', compact('exam', 'exam_user', 'variables', 'exam_subjects'));
    }

    public function printAll(Request $request, Exam $exam)
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

        if ($user->hasRole('student') !== true && $user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again'
            ], 401);
        }

        $allowed_exam_user_states = ['Active', 'Provisional', 'Completed'];
        $allowed_exam_user_state_ids = ExamUserState::whereIn('name', $allowed_exam_user_states)->pluck('id')->toArray();
        $offline_exam_type_id = ExamType::where('name', 'Offline')->first()->id;

        $exam_users = ExamUser::where('exam_id', $exam->id)->get();

        if ($exam->exam_type_id != $offline_exam_type_id) {
            return response([
                'header' => 'Invalid Request',
                'message' => 'Admit Card is issued only for offline exams'
            ], 401);
        }

        $allowed_users = [];

        foreach ($exam_users as $exam_user) {
            $user_ids = [];
            if (in_array($exam_user->exam_user_state_id, $allowed_exam_user_state_ids)) {
                array_push($user_ids, $exam_user->user_id);
            }

            $allowed_users = User::whereIn('id', $user_ids)->with('student.sectionStandard.standard')->get();
        }


        foreach ($allowed_users as $user) {
            $variables = VariableController::keyPairs();
            $exam_subjects = ExamSubjectForStudent::index($exam, $user);
            $file_name = str_replace(" ", "_", $exam->name) . '_' . str_replace(" ", "_", $exam->session->name) . '/' . $user->student->sectionStandard->standard->name . '/' . str_replace(" ", "_", $user->userDetail->name) . '.pdf';

            $pdf = PDF::loadView('documents.admit-card', compact('exam', 'exam_user', 'variables', 'exam_subjects'));
            // save the pdf file on the server
            $pdf->save(public_path('admit-cards/' . $file_name));
        }
    }

    public function show(Exam $exam)
    {
        $user = Auth::user();
        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        $exam_user = ExamUser::where('user_id', $user->id)->where('exam_id', $exam->id);
        if ($exam_user === null) {
            return response([
                'header' => 'Not Found',
                'message' => 'Something went wrong, Please contact principal!'
            ], 401);
        }
        return $exam_user->with('exam.examType', 'examUserState')->first();
    }
}
