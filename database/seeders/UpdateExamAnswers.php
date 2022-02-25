<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\ExamSubjectScore;

class UpdateExamAnswers extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exam_answers = ExamAnswer::get();
        foreach ($exam_answers as $answer) {
            $question_marks = $answer->examQuestion->marks;
            $exam_subject_id = $answer->examQuestion->examSubject->id;
            $user_id = $answer->user->id;
            $exam_subject_score = ExamSubjectScore::where('exam_subject_id', $exam_subject_id)->where('user_id', $user_id)->first();
            $marks_secured = $exam_subject_score->marks_secured;
            if($answer->examQuestion->exam_question_type_id == 2){
                $marks = rand(1, $question_marks);
                $answer->update([
                    'exam_answer_state_id' => 2,
                    'description' => $this->faker->sentence,
                    'marks_secured' => $marks,
                ]);

                $exam_subject_score->update([
                    'marks_secured' => $marks_secured + $marks,
                ]);
            }else{
                $exam_question_options = $answer->examQuestion->examQuestionOptions()->get();
                $random_option = $exam_question_options->random();
                $marks = $random_option->is_correct ? $question_marks : 0;
                $answer->update([
                    'exam_question_option_id' => $random_option->id,
                    'marks_secured' => $marks
                ]);

                $exam_subject_score->update([
                    'marks_secured' => $marks_secured + $marks,
                ]);
            }
        }
    }
}
