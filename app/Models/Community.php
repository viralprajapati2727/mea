<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Tag;

class Community extends Model
{

    use Sluggable;
    protected $guarded = [];

    protected $hidden = ["created_by", "updated_by", "deleted_by", "deleted_at"];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function communitySkill(){
        return $this->hasMany('App\Models\CommunitySkill', 'community_id','id');
    }
    public function communityComments(){
        return $this->hasMany('App\Models\CommunityComment', 'community_id');
    }
    public function communityLikes(){
        return $this->hasMany('App\Models\CommunityLike', 'community_id');
    }
    public function getTagsAttribute($value){
        $data = Tag::selectRaw('GROUP_CONCAT(title) As tags')->whereIn('id',explode(',',$value))->first()->toArray();
  
        return explode(',',$data['tags']);
    }
}
