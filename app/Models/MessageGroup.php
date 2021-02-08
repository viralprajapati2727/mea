<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    protected $guarded  = [];
    
    public $dates = ['created_at'];
    
    public $hidden = ['updated_at'];

    public function user(){
        return $this->belongsTo('App\User', 'created_by','id');
    }

    public function members(){
        return $this->hasMany('App\Models\MessageGroupMember', 'message_group_id', 'id');
    }

    public function messages() {
        return $this->hasMany('App\Models\Messages', 'message_group_id', 'id');
    }
}
