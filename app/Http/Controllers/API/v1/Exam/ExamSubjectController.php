<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamSubjectState;
use Illuminate\Support\Facades\Auth;

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

    public function all(Exam $exam)
    {
        $exam_subjects = ExamSubject::where('exam_id', $exam->id)->with('subject.standard', 'examSchedule', 'examSubjectState')->orderBy('subject_id')->get();
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
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'exam_id' => 'required|integer|exists:exams,id',
            'exam_schedule_id' => 'required|integer|exists:exam_schedules,id',
            'full_mark' => 'required|integer|between:10,200',
            'pass_mark' => 'required|integer|between:1,full_mark',
            'negative_percentage' => 'required|integer|between:0,400',
        ]);

        $examSubject->update([
            'exam_schedule_id' => $request->exam_schedule_id,
            'full_mark' => $request->full_mark,
            'pass_mark' => $request->pass_mark,
            'negative_percentage' => $request->negative_percentage,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Exam Subject Updated Successfully.'
        ], 200);
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
