<?php

namespace Database\Seeders;

use App\Models\Chargeable;
use Illuminate\Database\Seeder;

class ChargeableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chargeable::factory(20)->create();
    }
}
