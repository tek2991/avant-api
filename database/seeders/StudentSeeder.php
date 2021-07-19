<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gender;
use App\Models\Student;
use App\Models\BloodGroup;
use App\Models\SectionStandard;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
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
        $students = User::role('student')->get()->modelKeys();
        $sectionStandard = collect(SectionStandard::all()->modelKeys());

        foreach($students as $student){
            Student::factory()->create([
                'user_id' => $student,
                'section_standard_id' => $sectionStandard->random(),
            ]);
        }
    }
}
