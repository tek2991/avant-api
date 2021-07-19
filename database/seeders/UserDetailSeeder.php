<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UserDetailSeeder extends Seeder
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

        $users = User::where('username', '!=', 'administrator')->where('username', '!=', 'director')->get();

        foreach($users as $user){
            UserDetail::factory()->create([
                'user_id' => $user
            ]);
        }
    }
}
