<?php

namespace App\Http\Controllers\API\v1\Exam;

use Auth;
use App\Models\ExamAnswer;
use App\Models\ExamSubject;
use Illuminate\Http\Request;
use App\Models\ExamAnswerState;
use App\Models\ExamSubjectScore;
use App\Models\ExamSubjectState;
use App\Http\Controllers\Controller;

class ExamAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if ($user->hasRole('student') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }
        $this->validate($request, [
            'exam_subject_id' => 'required|integer|exists:exam_subject,id',
        ]);
        $exam_subject = ExamSubject::find($request->exam_subject_id);
        $exam_question_ids = $exam_subject->examQuestions()->pluck('id')->toArray();
        $user_id = $user->id;
        $exam_answer_created_state_id = ExamAnswerState::where('name', 'Created')->first()->id;
        $insert_data = [];
        foreach ($exam_question_ids as $exam_question_id) {
            $insert_data[] = [
                'exam_question_id' => $exam_question_id,
                'user_id' => $user_id,
                'exam_answer_state_id' => $exam_answer_created_state_id,
            ];
        }

        $exam_subject_active_state_id = ExamSubjectState::where('name', 'Active')->first()->id;
        $exam_subject_score = ExamSubjectScore::where('exam_subject_id', $exam_subject->id)->where('user_id', $user_id)->first();

        if($exam_subject_score->exam_subject_state_id != $exam_subject_active_state_id){
            ExamAnswer::insert($insert_data);
            $exam_subject_score->update([
                'exam_subject_state_id' => $exam_subject_active_state_id,
            ]);
            return response([
                'header' => 'Success',
                'message' => 'Online Exam started!',
            ], 200);
        }

        return response([
            'header' => 'Success',
            'message' => 'Online Exam rejoined!',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExamAnswer  $examAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(ExamAnswer $examAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExamAnswer  $examAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExamAnswer $examAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExamAnswer  $examAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExamAnswer $examAnswer)
    {
        //
    }
}
