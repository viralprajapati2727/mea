<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageGroupMember extends Model
{
    protected $guarded = [];

    public $hidden = ['updated_at'];

    public function messages() {
        return $this->hasMany('App\Models\Messages', 'message_group_id', 'id')->orderBy('id','DESC');
    }
    public function chatgroup(){
        return $this->belongsTo('App\Models\MessageGroup','message_group_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User','sender_id', 'id');
    }
}
