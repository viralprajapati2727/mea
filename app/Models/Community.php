<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Tags;
use App\Models\CommunityLikes;
use Laravelista\Comments\Commentable;
use Auth;
class Community extends Model
{

    use Sluggable, Commentable;

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
        return $this->hasMany('App\Models\CommunityLikes', 'community_id');
    }
    public static function countCommunityTotalLikes($community_id){
        return CommunityLikes::where('community_id',$community_id)->count();
    }
    public function communityTags(){
        return $this->hasMany('App\Models\CommunityTags', 'community_id');
    }
    public function communityCategory(){
        return $this->hasOne('App\Models\QuestionCategory', 'id', 'question_category_id')->select('id', 'title', 'status');
    }
    public function getTagsAttribute($value){
        $data = Tags::selectRaw('GROUP_CONCAT(title) As tags')->whereIn('id',explode(',',$value))->first()->toArray();
  
        return explode(',',$data['tags']);
    }

    public static function checkIsLikedByCurrentUser($community_id = 0){
        $data = false;
        if($community_id != 0){
            $data = communityLikes::where('community_id', $community_id)->where('user_id',Auth::id())->exists();
        }
        return $data;
    }
}
