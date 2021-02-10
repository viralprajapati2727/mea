<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $guarded = [];

    public $hidden = ['updated_at'];

    public function user(){
        return $this->belongsTo('App\User','sender_id', 'id')->select('id');
      }
      
    public function members(){
      return $this->belongsTo('App\Models\MessageGroupMember', 'sender_id', 'id');
    }

}
