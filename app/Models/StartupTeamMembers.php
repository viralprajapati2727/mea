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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function startup()
    {
        return $this->belongsTo(StartUpPortal::class, 'statup_id');
    }
}
