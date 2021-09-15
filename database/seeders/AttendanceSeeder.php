<?php

namespace Database\Seeders;

use App\Models\Session;
use App\Models\Student;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
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
        if(env('APP_ENV') !== 'local'){
            return;
        }
        
        $students = Student::get();

        $admin = 2;

        $session = $session = Session::where('is_active', true)->firstOrFail();
        $session_id = $session->id;

        $start = Carbon::parse($session->created_at)->startOfDay();
        $end = Carbon::parse($session->created_at)->startOfDay();
        $end = $end->addYear();
        $period = CarbonPeriod::create($start, $end);

        $data = [];

        foreach($students as $student){
            $user_id = $student->user->id;
            $section_standard_id = $student->sectionStandard->id;
            foreach ($period as $date) {
                $data[] = [
                    'user_id' => $user_id,
                    'attendance_state_id' => rand(2, 3),
                    'attendance_date' => $date,
                    'section_standard_id' => $section_standard_id,
                    'session_id' => $session_id,
                    'created_by' => $admin,
                    'updated_by' => $admin,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }

        $chunks = array_chunk($data, 50);
        foreach($chunks as $chunk){
            Attendance::insert($chunk);
        }
    }
}
