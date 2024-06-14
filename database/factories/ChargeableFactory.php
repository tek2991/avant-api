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
        $tax_rates = [5, 12, 18, 28];
        $k = array_rand($tax_rates);
        $amount = mt_rand(100, 4000)*100;
        $tax_rate = $tax_rates[$k];
        $gross_amount = $amount*(($tax_rate/100)+1);
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'is_mandatory' => true,
            'amount_in_cent' => $amount,
            'tax_rate' => $tax_rate,
            'gross_amount_in_cent' => $gross_amount,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
