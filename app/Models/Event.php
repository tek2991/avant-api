<?php

namespace App\Models;

use App\Models\User;
use App\Models\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type_id',
        'name',
        'description',
        'event_from_date',
        'event_to_date',
        'created_by',
        'updated_by',
    ];

    protected $cast = [
        'event_from_date' => 'datetime',
        'event_to_date' => 'datetime',
    ];

    protected $dates = ['event_from_date', 'event_to_date'];

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updator(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function eventType(){
        return $this->belongsTo(EventType::class);
    }
}
