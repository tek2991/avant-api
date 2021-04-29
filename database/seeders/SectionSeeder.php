<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Standard;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $standards = Standard::all();

        $sections = ['A', 'B', 'C', 'D', 'E'];

        foreach($sections as $section){
            Section::factory()->times(1)->create([
                'name' => $section,
            ]);

            // foreach($standards as $standard){
            //     $section_ = Section::where('name', $section)->pluck('id');
            //     $standard->sections()->attach($section_);
            // }
            Section::find('name', $section)->standards()->attach($standards);
        }
    }
}
