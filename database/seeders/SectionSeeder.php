<?php

namespace Database\Seeders;

use App\Models\Section;
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
        if(env('APP_ENV') !== 'local'){
            return;
        }
        $sections = ['A', 'B', 'C', 'Science', 'Commerce', 'Humanities'];

        foreach($sections as $section){
            Section::factory()->create([
                'name' => $section,
            ]);
        }
    }
}
