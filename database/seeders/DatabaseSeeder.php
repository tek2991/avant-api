<?php

namespace Database\Seeders;

use Database\Seeders\FeeSeeder;

use Illuminate\Database\Seeder;
use Database\Seeders\BillSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SectionSeeder;
use Database\Seeders\SessionSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\TeacherSeeder;
use Database\Seeders\StandardSeeder;
use Database\Seeders\ChargeableSeeder;
use Database\Seeders\FeeStandardSeeder;
use Database\Seeders\AppealTypeSeeder;
use Database\Seeders\AppealStateSeeder;
use Database\Seeders\ChargeableFeeSeeder;
use Database\Seeders\AttendenceStateSeeder;
use Database\Seeders\SectionStandardSeeder;
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
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            SessionSeeder::class,
            StandardSeeder::class,
            SectionSeeder::class,
            TeacherSeeder::class,
            SectionStandardSeeder::class,
            StudentSeeder::class,
            FeeSeeder::class,
            ChargeableSeeder::class,
            ChargeableFeeSeeder::class,
            FeeStandardSeeder::class,
            BillSeeder::class,
            AttendenceStateSeeder::class,
            AppealStateSeeder::class,
            AppealTypeSeeder::class,
        ]);
    }
}
