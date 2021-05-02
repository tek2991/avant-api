<?php

namespace Database\Factories;

use App\Models\Fee;
use App\Models\Chargeable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChargeableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chargeable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fees = collect(Fee::all()->modelKeys());
        $amount = mt_rand(100, 4000);
        $tax_rate = mt_rand(5, 28);
        $gross_amount = $amount*(($tax_rate/100)+1);
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'fee_id' => $fees->random(),
            'amount' => $amount,
            'tax_rate' => $tax_rate,
            'gross_amount' => $gross_amount,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
