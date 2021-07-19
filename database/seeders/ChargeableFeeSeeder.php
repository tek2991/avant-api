<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\Chargeable;
use Illuminate\Database\Seeder;

class ChargeableFeeSeeder extends Seeder
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
        $fees = Fee::all();
        $chargeables = Chargeable::all();

        for ($i=0; $i < 15; $i++) { 
            $fees->random()->chargeables()->syncWithoutDetaching($chargeables->random());
        }
    }}
