<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = array("Male","Female","Others");

        foreach($genders as $gender){
            Gender::create([
                'name' => $gender,
            ]);
        }
    }
}
