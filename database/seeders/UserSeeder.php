<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $director = User::factory()->create([
            'username' => 'director'
        ]);
        $director->assignRole('director');

        $teachers = User::factory()->times(10)->create();
        foreach($teachers as $teacher){
            $teacher->assignRole('teacher');
        }

        $students = User::factory()->times(500)->create();
        foreach($students as $student){
            $student->assignRole('student');
        }
    }
}
