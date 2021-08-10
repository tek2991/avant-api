<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
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

        $Instruments = ['Cash', 'Cheque/Draft', 'NEFT/IMPS/UPI', 'Others'];

        foreach($Instruments as $Instrument){
            Instrument::factory()->create([
                'name' => $Instrument,
            ]);
        }
    }
}
