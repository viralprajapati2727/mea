<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\KeySkill;

class PostJob extends Model
{
    protected $guarded = [];

    protected $hidden = ["created_by", "updated_by", "deleted_by", "deleted_at"];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User', 'user_id','id');
    }
    public function jobTitle(){
        return $this->belongsTo('App\Models\JobTitle', 'job_title_id','id');
    }
    public function category(){
        return $this->belongsTo('App\Models\BusinessCategory', 'business_category_id','id');
    }
    public function currency(){
        return $this->belongsTo('App\Models\Currency', 'currency_id','id');
    }
    public function jobSkill(){
        return $this->hasMany('App\Models\JobSkill', 'job_id','id');
    }
    public function jobShift(){
        return $this->hasMany('App\Models\JobShift', 'job_id','id');
    }
    public function jobApplied(){
        return $this->hasMany('App\Models\JobApplied', 'job_id','id')->orderBy('id','desc');
    }
    public static function generateBarcodeNumber() {

        $id = self::max('id') + 1;
        $jobId = "J".str_pad($id, 6, "0", STR_PAD_LEFT);
        if (self::jobIdExists($jobId)) {
            $jobId = substr($jobId, 1, strlen($jobId));
            $id = $jobId + 1;
            $jobId = "J".str_pad($id, 6, "0", STR_PAD_LEFT);
        }
        return $jobId;
    }
    public static function jobIdExists($jobId) {
        return self::where('job_unique_id',$jobId)->exists();
    }

    public function getKeySkillsAttribute($value){
        $data = KeySkill::selectRaw('GROUP_CONCAT(title) As skills')->whereIn('id',explode(',',$value))->first()->toArray();
  
        return explode(',',$data['skills']);
    }
}
