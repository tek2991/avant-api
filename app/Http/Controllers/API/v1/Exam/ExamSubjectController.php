<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam)
    {
        $exam_subjects = ExamSubject::where('exam_id', $exam->id)->with('subject.standard', 'examSchedule', 'examSubjectState')->orderBy('subject_id')->paginate();
        return $exam_subjects;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamSubject  $examSubject
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSubject $examSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamSubject  $examSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamSubject $examSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamSubject  $examSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamSubject $examSubject)
    {
        //
    }
}
