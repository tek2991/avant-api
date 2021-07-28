<?php

namespace Database\Factories;

use App\Models\Stream;
use App\Models\SubjectGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubjectGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $streams = collect(Stream::all()->modelKeys());

        return [
            'name' => $this->faker->word(),
            'stream_id' => $streams->random(),
        ];
    }
}
