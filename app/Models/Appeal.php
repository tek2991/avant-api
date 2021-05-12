<?php

namespace App\Models;

use App\Models\User;
use App\Models\AppealType;
use App\Models\AppealState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'appeal_type_id',
        'appeal_state_id',
        'title',
        'body',
        'remark',
        'appeal_from_date',
        'appeal_to_date'
    ];

    protected $cast = [
        'appeal_from_date' => 'datetime',
        'appeal_to_date' => 'datetime',
    ];

    protected $dates = ['appeal_from_date', 'appeal_to_date'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function appealType(){
        return $this->belongsTo(AppealType::class);
    }

    public function appealState(){
        return $this->belongsTo(AppealState::class);
    }
}
