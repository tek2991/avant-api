<?php

namespace App\Http\Controllers\API\v1\Exam;

use Exception;
use App\Models\Exam;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\Controller;
use App\Models\ExamSubjectState;
use Illuminate\Support\Facades\Auth;

class ExamScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam)
    {
        $examSchedules = $exam->examSchedules()->withCount('examSubjects')->orderBy('start')->paginate();
        return $examSchedules;
    }

    public function all(Exam $exam)
    {
        $examSchedules = $exam->examSchedules()->orderBy('start')->get();
        return $examSchedules;
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
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $examSchedule = ExamSchedule::create([
            'exam_id' => $request->exam_id,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Exam Schedule Created Successfully.',
            'data' => $examSchedule
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamSchedule  $examSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(ExamSchedule $examSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamSchedule  $examSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamSchedule $examSchedule)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        $examSchedule->update([
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response([
            'header' => 'Success',
            'message' => 'Exam Schedule Updated Successfully.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamSchedule  $examSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamSchedule $examSchedule)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true && $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        try {
            $examSchedule->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Exam Schedule is in use.'
            ], 400);
        }

        return response([
            'header' => 'Success',
            'message' => 'Exam Schedule Deleted Successfully.'
        ], 200);
    }

    public function control(ExamSchedule $examSchedule, Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true & $user->hasRole('teacher') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'status' => 'required|in:start,end'
        ]);

        $exam_subject_state_id = null;

        if ($request->status == 'start') {
            $exam_subject_state_id = ExamSubjectState::where('name', 'Active')->first()->id;
        } elseif ($request->status == 'end') {
            $exam_subject_state_id = ExamSubjectState::where('name', 'Evaluating')->first()->id;
        }

        $exam_subject_created_state_id = ExamSubjectState::where('name', 'Created')->first()->id;
        $exam_subject_active_state_id = ExamSubjectState::where('name', 'Active')->first()->id;

        $exam_subjects = $examSchedule->examSubjects()->get();

        foreach ($exam_subjects as $exam_subject) {
            if($request->status == 'start'){
                if($exam_subject->exam_subject_state_id == $exam_subject_created_state_id){
                    $exam_subject->update([
                        'exam_subject_state_id' => $exam_subject_state_id
                    ]);
                }
            }

            if($request->status == 'end'){
                if($exam_subject->exam_subject_state_id == $exam_subject_active_state_id){
                    $exam_subject->update([
                        'exam_subject_state_id' => $exam_subject_state_id
                    ]);
                }
            }
        }

        return response([
            'header' => 'Success',
            'message' => 'Exam Schedule Control Updated Successfully.'
        ], 200);
    }
}
