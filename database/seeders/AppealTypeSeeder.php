<?php

namespace Database\Seeders;

use App\Models\AppealType;
use Illuminate\Database\Seeder;

class AppealTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appealTypes = ['Leave Request'];

        foreach ($appealTypes as $appealType) {
            AppealType::create([
                'name' => $appealType
            ]);
        }
    }
}
