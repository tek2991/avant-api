<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsError extends Model
{
    protected $fillable = [
        'sms_template_id',
        'status_code',
        'message'
    ];

    public function smsTemplate(){
        return $this->belongsTo(SmsTemplate::class);
    }
}
