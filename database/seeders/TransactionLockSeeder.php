<?php

namespace Database\Seeders;

use App\Models\TransactionLock;
use Illuminate\Database\Seeder;

class TransactionLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = ["Billing"];

        foreach($transactions as $transaction){
            TransactionLock::create([
                "name" => $transaction,
                "locked" => false,
            ]);
        }
    }
}
