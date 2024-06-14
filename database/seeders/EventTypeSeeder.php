<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eventTypes = ['Exam' => 'yellow', 'Holiday' => 'red', 'Occasion' => 'green', 'Other' => 'blue'];

        foreach ($eventTypes as $eventType => $color) {
            EventType::create([
                'name' => $eventType,
                'color' => $color
            ]);
        }
    }
}
