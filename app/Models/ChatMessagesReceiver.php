<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ChatMessagesReceiver extends Model
{
    protected $fillable = [
        'id',
        'receiver_id',
        'group_id',
        'unreadable_count',
    ];
    public function user(){
        return $this->belongsTo('App\User','sender_id', 'id')->select('id','show_name','profile_img');
    }
    public function members(){
        return $this->hasMany('App\Models\ChatMasters', 'group_id', 'group_id')->where('user_id','!=',Auth::user()->id)->where('left_group',null);
    }
    
    public function chatgroup(){
        return $this->hasMany('App\Models\ChatGroup','id', 'group_id')->where('is_single','!=',1);
	}
}
