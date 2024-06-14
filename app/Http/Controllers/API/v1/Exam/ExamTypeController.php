<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Http\Controllers\Controller;
use App\Models\ExamType;
use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    public function index()
    {
        return ExamType::all();
    }
}
