<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMasters extends Model
{
    //
    protected $fillable = [
        'id',
		'user_id',
        'group_id',
		'clear_chat_time',
		'left_group',
		'created_at',	
    ];
	public $timestamps = false;
	
	public function messages() {
		return $this->hasMany('App\Models\ChatMessage', 'group_id', 'id')->orderBy('id','DESC');
	}
	public function chatgroup(){
        return $this->belongsTo('App\Models\ChatGroup','group_id', 'id');
	}
	public function user(){
        return $this->belongsTo('App\User','user_id', 'id')->select('id', 'name' ,'logo');
	}
	public function members(){
        return $this->hasMany('App\Models\ChatMasters','group_id', 'group_id');
	}
	// public function deletemsgs(){
    //     return $this->belongsTo('App\Models\DeleteMsgs','msg_id', 'id');
	// }
	public function teamMemberCount() {
		return $this->hasMany('App\Models\ChatMasters', 'group_id', 'group_id')->selectRaw("group_id,count(*) AS total_members")->groupBy('group_id');
	}
}
