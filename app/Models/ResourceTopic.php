<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceTopic extends Model
{
    protected $guarded = [];
    
    public $timestamps = false;

    public function resource(){
        return $this->belongsTo('App\Models\Resource', 'resource_id','id');
    }
    public function topic(){
        return $this->belongsTo('App\Models\Topic', 'topic_id','id');
    }
}
