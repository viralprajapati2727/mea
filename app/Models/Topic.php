<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];

    public function resources(){
        return $this->hasMany('App\Models\Resource', 'topic_id','id');
    }
}
