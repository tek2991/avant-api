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
        if(env('APP_ENV') !== 'local'){
            return;
        }

        $subjectGroups = SubjectGroup::get();
        $standards = Standard::all();
        $teachers = collect(Teacher::all()->modelKeys());

        foreach($subjectGroups as $subjectGroup){

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
