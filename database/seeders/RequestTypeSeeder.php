<?php

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Seeder;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requestTypes = ['Leave Request'];

        foreach ($requestTypes as $requestType) {
            RequestType::create([
                'name' => $requestType
            ]);
        }
    }
}
