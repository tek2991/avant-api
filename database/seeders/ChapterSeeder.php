<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
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

        $subjects = collect(Subject::all()->modelKeys());

        foreach($subjects as $subject){
            Chapter::factory(5)->create([
                'subject_id' => $subject,
            ]);
        }
    }
}
