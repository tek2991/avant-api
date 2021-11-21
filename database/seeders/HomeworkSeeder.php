<?php

namespace Database\Seeders;

use App\Models\Homework;
use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
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
        Homework::factory(10)->create();

        foreach(Homework::all() as $homework){
            $subject = $homework->subject;
            $students = $subject->students()->where('section_standard_id', $homework->section_standard_id)->get();

            $homework->students()->sync($students);
        }
    }
}
