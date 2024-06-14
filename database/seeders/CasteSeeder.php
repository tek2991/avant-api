<?php

namespace Database\Seeders;

use App\Models\Caste;
use Illuminate\Database\Seeder;

class CasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $castes = array("General", "OBC", "OBC (NC)", "SC", "ST", "ST (H)");

        foreach($castes as $caste){
            Caste::create([
                'name' => $caste,
            ]);
        }
    }
}
