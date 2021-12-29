<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;

class ExamQuestionController extends Controller
{
    public function index(ExamSubject $examSubject)
    {
        $examQuestions = ExamQuestion::where('exam_subject_id', $examSubject->id)->with('examQuestionOptions', 'examQuestionType')->paginate();
        return $examQuestions;
    }
}
