<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeAccount extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User', 'created_by','id');
    }
}
