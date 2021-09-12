<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'message_id',
        'sender_id',
        'message',
        'variable_count',
    ];

    public function smsRecords(){
        return $this->hasMany(SmsRecord::class);
    }

    public function smsErrors(){
        return $this->hasMany(SmsError::class);
    }
}
