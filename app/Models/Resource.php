<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes, Sluggable;
  
    protected $guarded = [];

    /**
     * @inheritDoc
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user(){
        return $this->belongsTo('App\User', 'created_by','id');
    }

    public function topics()
    {
        return $this->hasMany("App\Models\ResourceTopic", 'resource_id', 'id');
    }   
}
