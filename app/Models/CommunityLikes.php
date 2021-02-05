<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityLikes extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at','updated_at'];

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function communityLiks()
    {
        return $this->belongsTo('App\Models\Community', 'comminuty_id');
    }
}
