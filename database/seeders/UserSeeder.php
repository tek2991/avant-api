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

        $admin->userDetail()->create([
            'name' => 'The Administrator',
            'phone' => '1234567890',
            'phone_alternate' => '1234567890',
            'dob' => '1990-01-01',
            'gender_id' => '1',
            'blood_group_id' => '1',
            'fathers_name' => 'Not Entered',
            'mothers_name' => 'Not Entered',
            'address' => 'Not Entered',
            'pincode' => '781001',
            'pan_no' => 'Not Entered',
            'aadhar_no' => 'Not Entered',
            'dl_no'=> 'Not Entered',
            'voter_id'=> 'Not Entered',
            'passport_no'=> 'Not Entered',
        ]);

        $director = User::factory()->create([
            'username' => 'director',
            'email' => 'director@avant.com'
        ]);
        $director->assignRole('director');
        $director->assignRole('teacher');

        $director->userDetail()->create([
            'name' => 'The Principal',
            'phone' => '1234567890',
            'phone_alternate' => '1234567890',
            'dob' => '1990-01-01',
            'gender_id' => '1',
            'blood_group_id' => '1',
            'fathers_name' => 'Not Entered',
            'mothers_name' => 'Not Entered',
            'address' => 'Not Entered',
            'pincode' => '781001',
            'pan_no' => 'Not Entered',
            'aadhar_no' => 'Not Entered',
            'dl_no'=> 'Not Entered',
            'voter_id'=> 'Not Entered',
            'passport_no'=> 'Not Entered',
        ]);

        if(env('APP_ENV') !== 'local'){
            return;
        }

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
