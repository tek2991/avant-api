<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $message_ids = ['111043', '111044'];
        $sender_ids = ['WREPMS', 'WREPMS'];
        $messages = ['Respected Parents, school will remain closed on {#var#}', 'Respected parents, your child {#var#} was absent on {#var#}. Kindly contact Principal'];
        $variable_counts = ['1', '2'];

        foreach ($messages as $key => $value) {
            SmsTemplate::create([
                'message_id' => $message_ids[$key],
                'sender_id' => $sender_ids[$key],
                'message' => $value,
                'variable_count' => $variable_counts[$key],
            ]);
        }
    }
}
