<?php

namespace Database\Seeders;

use App\Models\AttendanceState;
use Illuminate\Database\Seeder;

class AttendanceStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendanceStates = ['Not taken', 'Present', 'Absent'];

        foreach ($attendanceStates as $attendanceState) {
            AttendanceState::create([
                'name' => $attendanceState
            ]);
        }
    }
}
