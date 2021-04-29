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
        $standards = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $hierachy = 10;

        foreach($standards as $standard){
            Standard::factory()->times(1)->create([
                'name' => $standard,
                'hierachy' => $hierachy
            ]);

            $hierachy+=10;
        }
    }
}
