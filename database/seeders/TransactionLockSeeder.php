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
        $transactions = [
            "Billing" => false,
            "Razorpay" => true,
            "SMS" => true,
            "OnlineExam" => true,
        ];

        foreach($transactions as $transaction => $status){
            TransactionLock::create([
                "name" => $transaction,
                "locked" => $status,
            ]);
        }
    }
}
