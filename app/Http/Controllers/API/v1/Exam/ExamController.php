<?php

namespace App\Http\Controllers\API\v1\Exam;

use App\Models\Exam;
use App\Models\Session;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\SectionStandard;
use App\Rules\ExamScheduleRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::with('examType')->paginate();
        return $exams;
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
            'name' => 'required|string',
            'description' => 'required|string',
            'exam_type_id' => 'required|integer|exists:exam_types,id',
            'exam_start_date' => 'required|date',
            'exam_end_date' => 'required|date',
            'class_ids' => 'required|array|exists:section_standard,id',
            'exam_schedules.*.start' => 'required|date',
            'exam_schedules.*.end' => 'required|date|after:exam_schedules.*.start',
        ]);

        $session_id = Session::where('is_active', true)->first()->id;

        $exam = Exam::create([
            'name' => $request->name,
            'description' => $request->description,
            'exam_type_id' => $request->exam_type_id,
            'exam_start_date' => $request->exam_start_date,
            'exam_end_date' => $request->exam_end_date,
            'session_id' => $session_id,
            'created_by' => $user->id,
        ]);

        foreach($request->exam_schedules as $exam_schedule) {
            $exam->examSchedules()->create([
                'start' => $exam_schedule['start'],
                'end' => $exam_schedule['end'],
            ]);
        }

        // $standard_ids = array_values(array_unique(SectionStandard::whereIn('id', $request->class_ids)->get()->pluck('standard_id')->toArray()));
        // $subjects = Subject::whereIn('standard_id', $standard_ids)->pluck('id')->toArray();
        $exam->sectionStandards()->sync($request->class_ids);
        // $exam->subjects()->attach($subjects);

        return response([
            'header' => 'Success',
            'message' => 'Exam Created Successfully.'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }
}
