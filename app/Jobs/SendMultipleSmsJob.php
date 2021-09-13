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

class SendMultipleSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sms_objects;
    public $template;
    public $route;
    public $url;
    public $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sms_objects, $template, $route, $url, $key)
    {
        $this->sms_objects = $sms_objects;
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
        foreach($this->sms_objects as $sms_object){
            $fields = [
                "sender_id" => $this->template->sender_id,
                "message" => $this->template->message_id,
                "variables_values" => implode("|", $sms_object["variables"]),
                "route" => $this->route,
                "numbers" => $sms_object["number"],
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
                    SmsRecord::create([
                        'sms_template_id' => $this->template->id,
                        'user_id' => $sms_object["user_id"],
                        'variables' => implode("|", $sms_object["variables"]),
                        'number' => $sms_object["number"],
                        'request_id' => $response->request_id,
                    ]);
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
}
