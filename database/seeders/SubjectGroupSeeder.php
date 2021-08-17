<?php

namespace Database\Seeders;

use App\Models\SubjectGroup;
use Illuminate\Database\Seeder;

class SubjectGroupSeeder extends Seeder
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
        $subjectGroups_1 = ['Science', 'Maths', 'English', 'Social Studies'];

        $subjectGroups_2 = ['Hindi', 'Assamese', 'Computer'];

        foreach($subjectGroups_1 as $subjectGroup){
            SubjectGroup::factory()->create([
                'name' => $subjectGroup,
                'stream_id' => '1',
            ]);
        }

        foreach($subjectGroups_2 as $subjectGroup){
            SubjectGroup::factory()->create([
                'name' => $subjectGroup,
                'stream_id' => '2',
            ]);
        }
    }
}
