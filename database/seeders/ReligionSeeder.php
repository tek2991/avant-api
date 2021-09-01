<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $religions = array("Hindu", "Islam", "Christian", "Sikh", "Buddhist", "Jain", "Unaffiliated", "Others");

        foreach($religions as $religion){
            Religion::create([
                'name' => $religion,
            ]);
        }
    }
}
