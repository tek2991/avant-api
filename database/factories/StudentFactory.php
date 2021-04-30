<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dob' => $this->faker->date,
            'roll_no' => mt_rand(1, 100),
            'fathers_name' => $this->faker->name($gender = 'male'),
            'mothers_name' => $this->faker->name($gender = 'female'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
