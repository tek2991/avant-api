<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
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
        
        $teachers = User::role('teacher')->get()->modelKeys();

        foreach($teachers as $teacher){
            Teacher::factory()->create([
                'user_id' => $teacher
            ]);
        }
    }
}
