<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaiseFund extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
