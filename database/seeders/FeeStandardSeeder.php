<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\Standard;
use Illuminate\Database\Seeder;

class FeeStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standards = Standard::all();
        $fees = collect(Fee::all());
        foreach($standards as $standard){
            $standard->fees()->attach($fees->random());
        }
    }
}
