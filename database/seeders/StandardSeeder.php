<?php

namespace Database\Seeders;

use App\Models\Standard;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standards = [];

        if (file_exists(__DIR__.'/../../keyValues.php')) {
            require __DIR__.'/../../keyValues.php';
            $standards = $standards_arr;
        }

        $hierachy = 10;

        foreach($standards as $standard){
            Standard::factory()->create([
                'name' => $standard,
                'hierachy' => $hierachy
            ]);

            $hierachy+=10;
        }
    }
}
