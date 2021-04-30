<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RollSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClassSeeder;
use Database\Seeders\SectionSeeder;
use Database\Seeders\SessionSeeder;
use Database\Seeders\StandardSeeder;
use Database\Seeders\RoleAndPermissionSeeder;


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
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            SessionSeeder::class,
            StandardSeeder::class,
            SectionSeeder::class,
            ClassSeeder::class,
            RollSeeder::class,
        ]);
    }
}
