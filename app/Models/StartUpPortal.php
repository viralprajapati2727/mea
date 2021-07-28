<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ScheduleAppointment;
use App\Models\StartupTeamMembers;

class StartUpPortal extends Model
{

    protected $guarded = [];
    public $timestamps = true;

    public function appoinment(){
        return $this->hasOne(ScheduleAppointment::class, 'startup_id', 'id');
    }

    public function startup_team_member(){
        return $this->hasMany(StartupTeamMembers::class, 'startup_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id','id');
    }
}
