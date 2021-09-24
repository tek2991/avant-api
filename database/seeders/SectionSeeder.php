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
        $sections = [];

        if (file_exists(__DIR__.'/../../keyValues.php')) {
            require __DIR__.'/../../keyValues.php';
            $sections = $sections_arr;
        }

        foreach($sections as $section){
            Section::factory()->create([
                'name' => $section,
            ]);
        }
    }
}
