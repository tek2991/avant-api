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
        if(env('APP_ENV') !== 'local'){
            return;
        }
        
        Notification::factory(10)->create();

        foreach(Notification::all() as $notification){
            $notification->users()->sync(User::all()->random(300));
        }
    }
}
