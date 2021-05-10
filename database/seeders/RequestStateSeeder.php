<?php

namespace Database\Seeders;

use App\Models\RequestState;
use Illuminate\Database\Seeder;

class RequestStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requestStates = ['Created', 'Recomemded', 'Approved', 'Rejected'];

        foreach ($requestStates as $requestState) {
            RequestState::create([
                'name' => $requestState
            ]);
        }
    }
}
