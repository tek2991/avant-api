<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamQuestionType;
use App\Http\Controllers\Controller;

class ExamQuestionTypeController extends Controller
{
    public function index(){
        $exam_question_types = ExamQuestionType::all();
        return $exam_question_types;
    }
}
