<?php

namespace Database\Seeders;

use App\Models\Stream;
use Illuminate\Database\Seeder;

class StreamSeeder extends Seeder
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
        $streams = ['Genaral', 'Optional'];

        foreach($streams as $stream){
            Stream::factory()->create([
                'name' => $stream,
            ]);
        }
    }
}
