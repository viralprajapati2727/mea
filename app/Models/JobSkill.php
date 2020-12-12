<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $guarded = [];
    
    public $timestamps = false;

    public function keySkill(){
        return $this->belongsTo('App\Models\KeySkill', 'key_skill_id','id');
    }
}
