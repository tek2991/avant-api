<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Session;
use App\Models\Student;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $student = Student::first();
        $user = $student->user;

        $admin = 2;

        $session = $session = Session::where('is_active', true)->firstOrFail();

        $start = new Carbon($session->created_at);
        $end = new Carbon($session->created_at);
        $end = $end->addYear();
        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            Attendance::create([
                'user_id' => $user->id,
                'attendance_date' => $date,
                'attendance_state_id' => random_int(2, 3),
                'section_standard_id' => $student->sectionStandard->id,
                'session_id' => $session->id,
                'created_by' => $admin,
                'updated_by' => $admin,
            ]);
        }
    }
}
