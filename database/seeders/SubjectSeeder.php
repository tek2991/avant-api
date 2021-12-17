<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Standard;
use App\Models\SubjectGroup;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $teachers = collect(Teacher::all()->modelKeys());
        $subjects = [];

        if (file_exists(__DIR__.'/../../keyValues.php')) {
            require __DIR__.'/../../keyValues.php';
            $subjects = $subjects_arr;
        }

        foreach($subjects as $subject => $standards_arr){
            $subjectGroup = SubjectGroup::create([
                'name' => $subject,
                'stream_id' => 1,
            ]);

            $standards = Standard::whereIn('name', $standards_arr)->get();

            foreach($standards as $standard){                
                $subject = Subject::create([
                    'name' => $subjectGroup->name,
                    'subject_group_id' => $subjectGroup->id,
                    'standard_id' => $standard->id,
                    'is_mandatory' => true,
                ]);

                $students = $standard->students()->get()->modelKeys();
                
                // Assign teacher for subject
                $subject->teachers()->syncWithoutDetaching($teachers->random());

                // Assign students to subject
                $subject->students()->sync($students);
            }
        }
    }
}
