<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Session;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $session_id = Session::where('is_active', true)->first()->id;

        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'session_id' => $session_id,
            'bill_from_date' => Carbon::today(),
            'bill_to_date' => Carbon::today()->addMonth()
        ];
    }
}
