<?php

namespace App\Http\Controllers\API\v1\Exam;

use Auth;
use App\Models\Exam;
use App\Models\ExamUser;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;
use App\Models\ExamType;
use App\Models\ExamUserState;

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
        $pas = PersonalAccessToken::findOrFail($model_id);

        if(!hash_equals($pas->token, hash('sha256', $token))){
            return response([
                'header' => 'Forbidden',
                'message' => 'You are not authorised to read this resource!'
            ], 401);
        }

        $user = $pas->tokenable;

        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $allowed_exam_user_states = ['Active', 'Provisional', 'Completed'];

        $allowed_exam_user_state_ids = ExamUserState::whereIn('name', $allowed_exam_user_states)->pluck('id')->toArray();
        $exam_user = ExamUser::where('user_id', $user->id)->where('exam_id', $exam->id)->first();

        $offline_exam_type_id = ExamType::where('name', 'Offline')->first()->id;
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

        $pdf = PDF::loadView('documents.admit-card', compact('exam', 'variables'));
        return $pdf->download('admit_card_1.pdf');

        // return view('documents.admit-card', compact('exam', 'variables'));
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
        return $exam_user->with('exam', 'examUserState')->first();
    }
}
