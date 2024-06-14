<?php

namespace Database\Seeders;

use App\Models\BloodGroup;
use Illuminate\Database\Seeder;

class BloodGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bloodGroups = array("O-", "O+", "A-", "A+", "B-", "B+", "AB-", "AB+");

        foreach($bloodGroups as $bloodGroup){
            BloodGroup::create([
                'name' => $bloodGroup,
            ]);
        }
    }
}
