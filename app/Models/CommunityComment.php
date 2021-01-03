<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityComment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['updated_at'];

    public function user(){
        return $this->hasOne('App\User','id','created_by');
    }

    public function community(){
        return $this->belongsTo('App\Models\Community','community_id');
    }

    public function singleSubComment(){
        return $this->hasOne('App\Models\CommunityComment','parent_id');
    }

    public function subComment(){
        return $this->hasMany('App\Models\CommunityComment','parent_id');
    }

    public function maincomment(){
        return $this->belongsTo('App\Models\CommunityComment','parent_id', 'id');
    }
}
