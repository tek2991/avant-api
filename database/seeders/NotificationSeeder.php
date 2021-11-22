<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::factory(10)->create();

        foreach(Notification::all() as $notification){
            $notification->users()->sync(User::all()->random(300));
        }
    }
}
