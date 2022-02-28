<?php

namespace App\Http\Controllers\API\v1\Exam;

use Exception;
use App\Models\Exam;
use App\Models\User;
use App\Models\ExamAnswer;
use App\Models\ExamSubject;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ExamSubjectScore;
use App\Models\ExamSubjectState;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\Controller;
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
            'status' => 'required|in:start,end,lock'
        ]);

        $exam_subject_state_id = null;

        // Check time constraints
        if ($request->status == 'start') {
            $exam_subject_state_id = ExamSubjectState::where('name', 'Active')->first()->id;
            $current_time_stamp = Carbon::now()->timestamp;
            $start_time_stamp = Carbon::parse($examSchedule->start)->timestamp;
            if ($current_time_stamp < $start_time_stamp) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'Exam Schedule is not started yet.'
                ], 403);
            }
        } elseif ($request->status == 'end') {
            $exam_subject_state_id = ExamSubjectState::where('name', 'Evaluating')->first()->id;
            $current_time_stamp = Carbon::now()->timestamp;
            $end_time_stamp = Carbon::parse($examSchedule->end)->timestamp;
            if ($current_time_stamp < $end_time_stamp) {
                return response([
                    'header' => 'Forbidden',
                    'message' => 'Exam Schedule is not ended yet.'
                ], 403);
            }
        }

        $exam_subject_created_state_id = ExamSubjectState::where('name', 'Created')->first()->id;
        $exam_subject_active_state_id = ExamSubjectState::where('name', 'Active')->first()->id;
        $exam_subject_evaluating_state_id = ExamSubjectState::where('name', 'Evaluating')->first()->id;
        $exam_subject_locked_state = ExamSubjectState::where('name', 'Locked')->first()->id;

        $exam_subjects = $examSchedule->examSubjects()->get();

        if ($request->status == 'start') {
            // Loop through exam subjects and check if they are ready
            foreach ($exam_subjects as $exam_subject) {
                $assigned_mark = $exam_subject->examQuestions->sum('marks');
                if ($assigned_mark != $exam_subject->full_mark) {
                    return response([
                        'header' => 'Error',
                        'message' => 'Unassigned marks in: ' . $exam_subject->subject->name . ' (' . $exam_subject->subject->standard->name . ')'
                    ], 400);
                }
            }

            foreach ($exam_subjects as $exam_subject) {
                if ($exam_subject->exam_subject_state_id == $exam_subject_created_state_id) {
                    $exam_subject->update([
                        'exam_subject_state_id' => $exam_subject_state_id
                    ]);

                    $student_ids = $exam_subject->subject->students->pluck('user_id')->toArray();
                    $user_ids = User::whereIn('id', $student_ids)->pluck('id')->toArray();
                    $pivot_data = [
                        'marks_secured' => 0,
                        'exam_subject_state_id' => $exam_subject_created_state_id,
                    ];
                    $sync_data = array_combine($user_ids, array_fill(0, count($user_ids), $pivot_data));
                    $exam_subject->users()->sync($sync_data);
                }
            }
            if ($examSchedule->started_at == null) {
                $examSchedule->update([
                    'started_at' => Carbon::now()
                ]);
            }
        }

        if ($request->status == 'end') {
            foreach ($exam_subjects as $exam_subject) {
                if ($exam_subject->exam_subject_state_id == $exam_subject_active_state_id) {
                    $exam_subject->update([
                        'exam_subject_state_id' => $exam_subject_state_id
                    ]);

                    $exam_subject_score_ids = $exam_subject->examSubjectScores->pluck('id')->toArray();

                    ExamSubjectScore::whereIn('id', $exam_subject_score_ids)->update([
                        'exam_subject_state_id' => $exam_subject_evaluating_state_id
                    ]);
                }
            }
            if ($examSchedule->ended_at == null) {
                $examSchedule->update([
                    'ended_at' => Carbon::now()
                ]);
            }
        }

        if ($request->status == 'lock') {
            foreach ($exam_subjects as $exam_subject) {
                if ($exam_subject->exam_subject_state_id !== $exam_subject_evaluating_state_id) {
                    return response([
                        'header' => 'Forbidden',
                        'message' => $exam_subject->subject->name . ' (' . $exam_subject->subject->standard->name . ') is not in evaluating state.'
                    ], 403);
                }

                $exam_question_ids = $exam_subject->examQuestions->pluck('id')->toArray();
                $count_null_marks_secured = ExamAnswer::whereIn('exam_question_id', $exam_question_ids)->whereNull('marks_secured')->count();
                if ($count_null_marks_secured > 0) {
                    $pending_answer = ExamAnswer::whereIn('exam_question_id', $exam_question_ids)->whereNull('marks_secured')->first();
                    return response([
                        'header' => 'Forbidden',
                        'message' => 'Evaluation incomplete in ' . $exam_subject->subject->name . ' (' . $exam_subject->subject->standard->name . ') for ' . $pending_answer->user->userDetail->name,
                    ], 403);
                }
            }

            $exam_subject_ids = $exam_subjects->pluck('id')->toArray();
            $exam_subject_score_ids = ExamSubjectScore::whereIn('exam_subject_id', $exam_subject_ids)->pluck('id')->toArray();

            ExamSubject::whereIn('id', $exam_subject_ids)->update([
                'exam_subject_state_id' => $exam_subject_locked_state
            ]);

            ExamSubjectScore::whereIn('id', $exam_subject_score_ids)->update([
                'exam_subject_state_id' => $exam_subject_locked_state
            ]);
        }



        return response([
            'header' => 'Success',
            'message' => 'Exam Schedule Control Updated Successfully.'
        ], 200);
    }
}
