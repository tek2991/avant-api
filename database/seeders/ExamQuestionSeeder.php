<?php

namespace Database\Seeders;

use App\Models\ExamSubject;
use App\Models\ExamQuestion;
use App\Models\ExamSchedule;
use Database\Factories\ExamQuestionFactory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ExamQuestionSeeder extends Seeder
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
        $exam_subjects = ExamSubject::all();

        foreach ($exam_subjects as $exam_subject) {
            $exam_schedule_ids = ExamSchedule::where('exam_id', $exam_subject->exam_id)->pluck('id')->toArray();
            $full_mark = $exam_subject->full_mark;
            $assign_marks = $exam_subject->examQuestions->sum('marks');

            for ($i = 0; $i <= 100; $i++) {
                if ($assign_marks >= $full_mark) {
                    break;
                }
                $marks = $full_mark - $assign_marks > 10 ? rand(1, 10) : rand(1, $full_mark - $assign_marks);
                $assign_marks += $marks;
                $random_question_type = rand(1, 2);
                if ($random_question_type == 2) {
                    ExamQuestion::factory()->create([
                        'exam_subject_id' => $exam_subject->id,
                        'exam_question_type_id' => $random_question_type,
                        'marks' => $marks,
                    ]);
                } else {
                    $correct_option = rand(1, 4);
                    $question = ExamQuestion::factory()->create([
                        'exam_subject_id' => $exam_subject->id,
                        'exam_question_type_id' => $random_question_type,
                        'marks' => $marks,
                    ]);

                    $question->examQuestionOptions()->createMany([
                        [
                            'description' => $this->faker->sentence,
                            'is_correct' => $correct_option == 1 ? true : false,
                        ],
                        [
                            'description' => $this->faker->sentence,
                            'is_correct' => $correct_option == 2 ? true : false,
                        ],
                        [
                            'description' => $this->faker->sentence,
                            'is_correct' => $correct_option == 3 ? true : false,
                        ],
                        [
                            'description' => $this->faker->sentence,
                            'is_correct' => $correct_option == 4 ? true : false,
                        ],
                    ]);
                }
            }
        }
    }
}
