<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityTags extends Model
{
    protected $guarded = [];
    
    public $timestamps = false;

    public function tag(){
        return $this->belongsTo('App\Models\Tag', 'tag_id','id');
    }
}
