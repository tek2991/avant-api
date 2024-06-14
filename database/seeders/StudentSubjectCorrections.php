<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StudentSubjectCorrections extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all_students = \App\Models\Student::all();
        foreach($all_students as $student){
            $standard = $student->sectionStandard->standard;
            $subjects = $standard->subjects()->get()->modelKeys();
            $student->subjects()->sync($subjects);
        }
    }
}
