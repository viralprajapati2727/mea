<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = "user_profiles";

    protected $hidden = ['created_at','updated_at'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
