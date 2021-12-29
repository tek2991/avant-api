<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamQuestionController extends Controller
{
    public function index(ExamSubject $examSubject)
    {
        return $examSubject->examQuestions()->with('examQuestionOptions', 'examQuestionType')->paginate();
    }
}
