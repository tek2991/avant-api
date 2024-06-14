<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chapter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subjects = collect(Subject::all()->modelKeys());

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(6, true),
            'subject_id' => $subjects->random(),
        ];
    }
}
