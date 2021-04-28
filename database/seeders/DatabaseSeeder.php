<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SessionSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // \App\Models\User::factory(1)->create();
        

        $this->call([
            RolesAndPermissionsSeeder::class,
            SessionSeeder::class
        ]);
    }
}
