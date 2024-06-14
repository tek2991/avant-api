<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationType;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notification_types = array('alert', 'warning', 'danger', 'success');

        foreach($notification_types as $notification_type){
            NotificationType::create([
                'name' => $notification_type,
            ]);
        }
    }
}
