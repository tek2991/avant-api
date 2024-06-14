<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = array(
         "State Bank of India",
         "Punjab National Bank",
         "Bank of Baroda",
         "Canara Bank",
         "Union Bank of India",
         "Bank of India",
         "Indian Bank",
         "Central Bank of India",
         "Indian Overseas Bank",
         "UCO Bank",
         "Bank of Maharashtra",
         "Punjab & Sindh Bank",
         "Axis Bank",
         "Bandhan Bank",
         "Catholic Syrian Bank",
         "City Union Bank",
         "DCB Bank",
         "Dhanlaxmi Bank",
         "Federal Bank",
         "HDFC Bank",
         "ICICI Bank",
         "IDBI Bank",
         "IDFC First Bank",
         "IndusInd Bank",
         "Jammu & Kashmir Bank",
         "Karnataka Bank",
         "Karur Vysya Bank",
         "Kotak Mahindra Bank",
         "Lakshmi Vilas Bank",
         "Nainital Bank",
         "RBL Bank",
         "South Indian Bank",
         "Tamilnad Mercantile Bank Limited",
         "Yes Bank",
         "Assam Co-operative Apex Bank");

        foreach($banks as $bank){
            Bank::create([
                'name' => $bank,
            ]);
        }
    }
}
