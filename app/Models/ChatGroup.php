<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Models\DeleteMsgs;
use App\Models\ChatMessagesReceiver;
use Auth;

class ChatGroup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'group_image',
        'group_name',
        'is_single',
		'created_by',
	];

    protected $dates = ['deleted_at'];
    // public $timestamps = false;    
    public function members(){
        return $this->hasMany('App\Models\ChatMasters', 'group_id', 'id');
    }
    public function messages() {
        // $deleted = DeleteMsgs::select('msg_id')->where('user_id',Auth::user()->id)->pluck('msg_id')->toArray();
		// return $this->hasMany('App\Models\ChatMessage', 'group_id', 'id')->whereNotIn('id', $deleted);
		return $this->hasMany('App\Models\ChatMessage', 'group_id', 'id');
    }
    public function unread() {
		return $this->hasMany('App\Models\ChatMessagesReceiver', 'group_id', 'id');
    }
    // public function deletemsgs(){
    //     return $this->hasMany('App\Models\DeleteMsgs','group_id', 'id');
    // }
    public function groupUsers() {
        return $this->hasManyThrough('App\User', 'App\Models\ChatMasters','group_id', 'id', 'user_id');
    }
    public function createdBy() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
    
}
