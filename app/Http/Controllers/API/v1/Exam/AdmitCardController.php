<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\API\v1\Attributes\VariableController;
use Auth;

class AdmitCardController extends Controller
{
    public function print(Request $request, Exam $exam){
        // if (strpos($request->pat, '|') === false) {
        //     return response([
        //         'header' => 'Forbidden',
        //         'message' => 'You are not authorised to read this resource!'
        //     ], 401);
        // }

        // $patArray = explode("|", $request->pat);
        // $model_id = $patArray[0];
        // $token = $patArray[1];
        // $pas = PersonalAccessToken::findOrFail($model_id);

        // if(!hash_equals($pas->token, hash('sha256', $token))){
        //     return response([
        //         'header' => 'Forbidden',
        //         'message' => 'You are not authorised to read this resource!'
        //     ], 401);
        // }

        // $user = $pas->tokenable;

        $variables = VariableController::keyPairs();

        $pdf = PDF::loadView('documents.admit-card', compact('exam', 'variables'));
        return $pdf->download('admit_card_1.pdf');

        // return view('documents.admit-card', compact('exam', 'variables'));
    }

    public function show(Exam $exam){
        $user = Auth::user();
        
    }
}
