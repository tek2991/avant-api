<?php

namespace Database\Seeders;

use App\Models\ExamUserState;
use Illuminate\Database\Seeder;

class ExamUserStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            'Active',
            'Inactive',
            'Completed',
            'Provisional'
        ];

        foreach ($states as $state) {
            ExamUserState::create([
                'name' => $state
            ]);
        }
    }
}
