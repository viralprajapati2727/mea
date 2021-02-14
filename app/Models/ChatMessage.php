<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'sender_id',
        'text',
        'group_id',
        'is_forwarded',
        // 'delete_for_me',
        // 'delete_for_all',
        'msg_type',
        'created_at',
    ];
    
    protected $dates = ['deleted_at'];
    // public $timestamps = false;
    
    public function user(){
      return $this->belongsTo('App\User','sender_id', 'id');
    }
    // public function deletemsgs(){
    //   return $this->belongsTo('App\Models\DeleteMsgs','msg_id', 'id');
    // }
    public function members(){
      return $this->belongsTo('App\Models\ChatMasters', 'sender_id', 'user_id');
    }
	  public function teamMember() {
		  return $this->hasMany('App\Models\ChatMasters', 'group_id', 'group_id');
    }
}
