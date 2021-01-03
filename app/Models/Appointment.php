<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User', 'user_id','id');
    }
}
