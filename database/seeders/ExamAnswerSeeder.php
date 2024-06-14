<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use App\Models\ExamSubject;
use App\Models\ExamSchedule;
use App\Models\ExamAnswerState;
use Illuminate\Database\Seeder;
use App\Models\ExamSubjectScore;
use App\Models\ExamSubjectState;

class ExamAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $started_exam_schedule_ids = ExamSchedule::whereNotNull('started_at')->whereNull('ended_at')->pluck('id')->toArray();
        $exam_subjects = ExamSubject::whereIn('exam_schedule_id', $started_exam_schedule_ids)->get();
        $exam_answer_created_state_id = ExamAnswerState::where('name', 'Created')->first()->id;
        $exam_subject_active_state_id = ExamSubjectState::where('name', 'Active')->first()->id;
        
        foreach ($exam_subjects as $exam_subject) {
            $users = $exam_subject->users()->get();
            
            foreach ($users as $user) {
                $exam_subject_score = ExamSubjectScore::where('exam_subject_id', $exam_subject->id)->where('user_id', $user->id)->first();
                $exam_question_ids = $exam_subject->examQuestions()->pluck('id')->toArray();
                $user_id = $user->id;
                $insert_data = [];
                foreach ($exam_question_ids as $exam_question_id) {
                    $insert_data[] = [
                        'exam_question_id' => $exam_question_id,
                        'user_id' => $user_id,
                        'exam_answer_state_id' => $exam_answer_created_state_id,
                    ];
                }


                if ($exam_subject_score->exam_subject_state_id != $exam_subject_active_state_id) {
                    ExamAnswer::insert($insert_data);
                    $exam_subject_score->update([
                        'exam_subject_state_id' => $exam_subject_active_state_id,
                    ]);
                }
            }
        }
    }
}
