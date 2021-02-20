<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StartUpPortal;
use User;

class ScheduleAppointment extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function startup(){
        return $this->belongsTo(StartUpPortal::class, 'startup_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
