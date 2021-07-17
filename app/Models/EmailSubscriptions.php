<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscriptions extends Model
{
    protected $table = "subscription_emails";

    protected $guarded = [];

    public $timestamps = false;
}
