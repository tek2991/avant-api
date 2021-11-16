<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
            VariableSeeder::class,
            TransactionLockSeeder::class,
            RoleAndPermissionSeeder::class,
            BloodGroupsSeeder::class,
            GenderSeeder::class,
            LanguageSeeder::class,
            ReligionSeeder::class,
            CasteSeeder::class,
            UserSeeder::class,
            UserDetailSeeder::class,
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
            AttendanceStateSeeder::class,
            AppealStateSeeder::class,
            AppealTypeSeeder::class,
            PaymentMethodSeeder::class,
            BankSeeder::class,
            InstrumentSeeder::class,
            EventTypeSeeder::class,
            StreamSeeder::class,
            SubjectGroupSeeder::class,
            SubjectSeeder::class,
            ChapterSeeder::class,
            SmsTemplateSeeder::class,
            ExamAttributesSeeder::class,
            HomeworkSeeder::class,
        ]);
    }
}
