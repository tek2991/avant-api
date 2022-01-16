<?php

namespace App\Http\Controllers\API\v1\Exam;

use Exception;
use App\Models\Exam;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
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
}
