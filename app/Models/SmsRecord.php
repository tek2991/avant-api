<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsRecord extends Model
{
    protected $fillable = [
        'sms_template_id',
        'user_id',
        'variables',
        'number',
        'request_id',
    ];

    public function smsTemplate(){
        return $this->belongsTo(SmsTemplate::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
