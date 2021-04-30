<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = User::role('student')->get()->modelKeys();

        foreach($students as $student){
            Student::factory()->create([
                'user_id' => $student,
            ]);
        }
    }
}
