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
        Chargeable::factory(5)->create();
        Chargeable::factory(2)->create([
            'is_mandatory' => false
        ]);

        $mandatoryChargeables = Chargeable::where('is_mandatory', true)->get();

        foreach($mandatoryChargeables as $mandatoryChargeable){
            $mandatoryChargeable->students()->attach(Student::get());
        }
    }
}
