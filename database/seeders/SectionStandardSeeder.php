<?php

namespace Database\Seeders;


use App\Models\Section;
use App\Models\Teacher;
use App\Models\Standard;
use Illuminate\Database\Seeder;

class SectionStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standards = Standard::all();
        $sections = Section::all();
        $teachers = collect(Teacher::all()->modelKeys());
        foreach($standards as $standard){
            $standard->sections()->attach($sections, [
                'teacher_id' => $teachers->random()
            ]);
        }
    }
}
