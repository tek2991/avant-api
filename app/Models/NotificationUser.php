<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NotificationUser extends Pivot
{
    protected $fillable = [
        'notification_id',
        'user_id',
    ];

    public function notification(){
        return $this->belongsTo(Notification::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
