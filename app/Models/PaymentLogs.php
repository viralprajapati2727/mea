<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLogs extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function stripe(){
        return $this->belongsTo('App\StripeAccount', 'stripe_id', 'id');
    }

}
