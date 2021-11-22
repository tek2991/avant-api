<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Notification;
use App\Models\NotificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $notification_types = NotificationType::all()->modelKeys();
        $events = Event::all()->modelKeys();

        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'created_by' => $this->faker->numberBetween(1, 10),
            'updated_by' => $this->faker->numberBetween(1, 10),
            'notification_type_id' => $this->faker->randomElement($notification_types),
            'event_id' => $this->faker->randomElement($events),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
