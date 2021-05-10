<?php

namespace Database\Seeders;

use App\Models\AttendenceState;
use Illuminate\Database\Seeder;

class AttendenceStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendenceStates = ['Not taken', 'Present', 'Absent', 'Leave applied', 'Leave approved', 'Leave rejected'];

        foreach ($attendenceStates as $attendenceState) {
            AttendenceState::create([
                'name' => $attendenceState
            ]);
        }
    }
}
