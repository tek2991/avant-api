<?php

namespace Database\Seeders;

use App\Models\AppealState;
use Illuminate\Database\Seeder;

class AppealStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appealStates = ['Created', 'Approved', 'Rejected'];

        foreach ($appealStates as $appealState) {
            AppealState::create([
                'name' => $appealState
            ]);
        }
    }
}
