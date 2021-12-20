<?php

namespace App\Http\Controllers\API\v1\Exam;

use Exception;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Models\ExamSectionStandard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamSectionStandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam)
    {
        $examSections = ExamSectionStandard::where('exam_id', $exam->id)->with('sectionStandard.section', 'sectionStandard.standard')->orderBy('section_standard_id')->paginate();
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
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'exam_id' => 'required|integer|exists:exams,id',
            'section_standard_id' => 'required|integer|exists:section_standard,id',
        ]);

        $exam = Exam::findOrFail($request->exam_id);
        $sectionStandard = SectionStandard::findOrFail($request->section_standard_id);

        $sync = $exam->sectionStandards()->syncWithoutDetaching([$sectionStandard->id]);

        return response([
            'header' => 'Success',
            'message' => 'Exam Section Standard Created Successfully.',
            'data' => $sync,
        ], 201);
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
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        try {
            $examSectionStandard->delete();
            return response([
                'header' => 'Success',
                'message' => 'Exam Section Standard Deleted Successfully.'
            ], 200);
        } catch (Exception $e) {
            return response([
                'header' => 'Error',
                'message' => 'Exam Section Standard Not Deleted.'
            ], 500);
        }
    }
}
