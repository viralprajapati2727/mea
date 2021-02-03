<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplied extends Model
{
    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function job(){
        return $this->belongsTo('App\Models\PostJob', 'job_id', 'id');
    }
}
