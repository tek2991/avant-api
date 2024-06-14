<?php

namespace Database\Factories;


use App\Models\Session;
use App\Models\Homework;
use Illuminate\Support\Carbon;
use App\Models\SectionStandard;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Homework::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $section_standard = SectionStandard::inRandomOrder()->first();
        $subject = $section_standard->standard->subjects()->inRandomOrder()->first();

        return [
            'session_id' => Session::where('is_active', true)->first()->id,
            'section_standard_id' => $section_standard->id,
            'subject_id' => $subject->id,
            'chapter_id' => $subject->chapters()->inRandomOrder()->first()->id,
            'description' => $this->faker->sentence($nbWords = 12, $variableNbWords = true),
            'created_by' => $subject->teachers()->inRandomOrder()->first()->user->id,
            'homework_from_date' => Carbon::today(),
            'homework_to_date' => Carbon::today()->addDay(),
        ];
    }
}
