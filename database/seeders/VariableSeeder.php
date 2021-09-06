<?php

namespace Database\Seeders;

use App\Models\Variable;
use Illuminate\Database\Seeder;

class VariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variables = [];

        if (file_exists(__DIR__.'/../../keyValues.php')) {
            require __DIR__.'/../../keyValues.php';
            $variables = $keyValues;
        }

        foreach ($variables as $key => $value) {
            Variable::create([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
}
