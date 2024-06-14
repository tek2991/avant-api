<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'notification_type_id',
        'event_id',
    ];

    public function notificationType(){
        return $this->belongsTo(NotificationType::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updator(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function users(){
        return $this->belongsToMany(User::class)->withPivot('id')->withTimestamps();
    }
}
