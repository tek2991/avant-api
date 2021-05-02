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
        $admin = User::factory()->create([
            'username' => 'administrator',
            'email' => 'admin@avant.com'
        ]);
        $admin->assignRole('admin');

        $director = User::factory()->create([
            'username' => 'director'
        ]);
        $director->assignRole('director');

        $teachers = User::factory(60)->create();
        foreach($teachers as $teacher){
            $teacher->assignRole('teacher');
        }

        $students = User::factory(600)->create();
        foreach($students as $student){
            $student->assignRole('student');
        }
    }
}
