<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $event_types = EventType::all()->modelKeys();
        $event_date_from = $this->faker->dateTimeBetween('+0 days', '+2 months');
        $event_date_from_clone = clone $event_date_from;
        return [
            'event_type_id' => $this->faker->randomElement($event_types),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'created_by' => $this->faker->numberBetween(1, 10),
            'updated_by' => $this->faker->numberBetween(1, 10),
            'event_from_date' => $event_date_from,
            'event_to_date' => $this->faker->dateTimeBetween($event_date_from, $event_date_from_clone->modify('+10 days')),
        ];
    }
}
