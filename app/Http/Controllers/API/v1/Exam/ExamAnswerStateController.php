<?php

namespace App\Http\Controllers\API\v1\Exam;

use Illuminate\Http\Request;
use App\Models\ExamAnswerState;
use App\Http\Controllers\Controller;

class ExamAnswerStateController extends Controller
{
    public function index(){
        $examAnswerStates = ExamAnswerState::all();
        return $examAnswerStates;
    }
}
