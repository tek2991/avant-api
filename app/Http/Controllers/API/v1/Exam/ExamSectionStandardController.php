<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSectionStandard;
use Illuminate\Http\Request;

class ExamSectionStandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam)
    {
        $examSections = $exam->examSections()->orderBy('id')->paginate();
        return $examSections;
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
     * @param  \App\Models\ExamSectionStandard  $examSectionStandard
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSectionStandard $examSectionStandard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamSectionStandard  $examSectionStandard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamSectionStandard $examSectionStandard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamSectionStandard  $examSectionStandard
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamSectionStandard $examSectionStandard)
    {
        //
    }
}
