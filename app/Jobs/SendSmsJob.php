<?php

namespace App\Jobs;

use App\Models\SmsError;
use App\Models\SmsRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $variables;
    public $numbers;
    public $user_ids;
    public $template;
    public $route;
    public $url;
    public $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($variables, $numbers, $user_ids, $template, $route, $url, $key)
    {
        $this->variables = $variables;
        $this->numbers = $numbers;
        $this->user_ids = $user_ids;
        $this->template = $template;
        $this->route = $route;
        $this->url = $url;
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fields = [
            "sender_id" => $this->template->sender_id,
            "message" => $this->template->message_id,
            "variables_values" => implode("|", $this->variables),
            "route" => $this->route,
            "numbers" => implode(",", $this->numbers),
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: " . $this->key,
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        $res = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Storage::put('sms_error.txt', $err);
        } else {
            $response = json_decode($res);
            
            if ($response->return == true) {
                $data = [];
                foreach ($this->user_ids as $key => $value) {
                    $data[] = [
                        'sms_template_id' => $this->template->id,
                        'user_id' => $value,
                        'variables' => implode("|", $this->variables),
                        'number' => $this->numbers[$key],
                        'request_id' => $response->request_id,
                        'created_at' => now()->toDateTimeString(),
                        'updated_at' => now()->toDateTimeString(),
                    ];
                }
                SmsRecord::insert($data);
            } else {
                SmsError::create([
                    'sms_template_id' => $this->template->id,
                    'status_code' => $response->status_code,
                    'message' => $response->message,
                ]);
            }
        }
    }
}
