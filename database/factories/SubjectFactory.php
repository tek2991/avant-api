<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Standard;
use App\Models\SubjectGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subjectGroups= collect(SubjectGroup::all()->modelKeys());
        $standards = collect(Standard::all()->modelKeys());

        return [
            'name' => $this->faker->word(),
            'subject_group_id' => $subjectGroups->random(),
            'standard_id' => $standards->random(),
            'is_mandatory' => mt_rand(0, 1),
        ];
    }
}
