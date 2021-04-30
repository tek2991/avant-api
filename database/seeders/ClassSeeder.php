<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Standard;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standards = Standard::all();
        $sections = Section::all();
        foreach($standards as $standard){
            $standard->sections()->attach($sections);
        }
    }
}
