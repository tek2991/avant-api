<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\ExamSubject;
use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $exam_subject_ids = ExamSubject::all()->pluck('id')->toArray();
        $exam_question_type_ids = ExamQuestion::all()->pluck('id')->toArray();
        $director_user_id = User::where('username', 'director')->first()->id;
        return [    
            'exam_subject_id' => $this->faker->randomElement($exam_subject_ids),
            'chapter_id' => null,
            'exam_question_type_id' => $this->faker->randomElement($exam_question_type_ids),
            'description' => $this->faker->sentence,
            'marks' => $this->faker->numberBetween(1, 10),
            'max_time_in_seconds' => $this->faker->numberBetween(1, 100),
            'created_by' => $director_user_id,
        ];
    }
}
