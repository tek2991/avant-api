<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Chargeable;
use Illuminate\Database\Seeder;

class ChargeableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') !== 'local') {
            return;
        }
        // Chargeable::factory(5)->create();
        Chargeable::factory()->create([
            'is_mandatory' => true,
            'name' => 'Outstanding balance',
            'description' => 'Outstanding balance upto Oct 2021',
        ]);

        $mandatoryChargeables = Chargeable::where('is_mandatory', true)->get();

        foreach ($mandatoryChargeables as $mandatoryChargeable) {
            $mandatoryChargeable->students()->attach(Student::get());
        }
    }
}
