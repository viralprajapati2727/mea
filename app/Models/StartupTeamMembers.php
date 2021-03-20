<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StartUpPortal;
use App\User;

class StartupTeamMembers extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','slug','email');
    }

    public function startupDetails(){
        return $this->belongsTo(StartUpPortal::class, 'startup_id','id')->select('id','name','user_id','description','industry','location','stage_of_startup','status');
    }
}
