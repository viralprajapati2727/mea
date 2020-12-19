<?php

namespace App\Http\Controllers;

use App\User;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;
use stdClass;
use App\Models\JobTitle;
use App\Models\Currency;
use App\Models\PostJob;
use App\Models\JobSkill;
use App\Models\KeySkill;



class JobController extends Controller
{
    public function index(){

        $jobs = Helper::getJobData(Auth::id(),null,null,true,10);

        return view('job.my-jobs',compact('jobs'));
    }
    /**
     * opening user fill profile form.
     *
     * @return \Illuminate\Http\Response
     */
    public function fillJob($job_unique_id = null)
    {
        try{
            $job = null;
            $jobtitles = JobTitle::where('deleted_at',null)->get();
            $currencies = Currency::where('deleted_at',null)->get();
            
            $skills = KeySkill::select('title As label')->get()->toArray();

            if(!empty($job_unique_id) && !is_null($job_unique_id)){
                $job = Helper::getJobData(Auth::id(),$job_unique_id,null,false,null);
            }
			return view('job.fill-job',compact('jobtitles','currencies','job','skills'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }
    
    public function updateJob(Request $request){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        DB::beginTransaction();

        try{
            $is_pending_job = $both = 0;
            $user_id = Auth::id();

            // if(!$request->has('job_id')){
            //     $check_job = Helper::checkingJobData($user_id);
            //     if(!empty($check_job)){
            //         $is_pending_job = 1;
            //         return response()->json(['both' => $both,  'message' => "", 'status' => 0,'is_pending_job' => $is_pending_job]);
            //     }
            // }
            
            $job_unique_id = PostJob::generateBarcodeNumber();

            if($request->has('job_id')){
                $param[ "id" ] = $request->job_id;
            }

            $param2 = ["job_title_id" => $request->job_title_id, "job_type_id" => $request->job_type_id, "currency_id" => $request->currency_id,
            "min_salary" => $request->min_salary,"max_salary" => $request->max_salary, "job_start_time" => $request->job_start_time,"job_end_time" => $request->job_end_time,
            "description" => $request->description,"location" => $request->location,"required_experience" => $request->required_experience,"created_by" => $user_id, "updated_by" => $user_id];

            $skills = explode(',',preg_replace('/\s*,\s*/', ',', $request->key_skills));
            $skillArr = [];
            foreach ($skills as $k => $skill) {
                if(!empty($skill)){
                    $skillModel = KeySkill::firstOrCreate(['title' => $skill],["created_by" => $user_id]);

                    if(isset($skillModel) && !empty($skillModel) && $skillModel->count() > 0){
                        $skillArr[] = $skillModel->id;
                    }
                }
            }
            if(!empty($skillArr)){
                $param2[ "key_skills" ] = implode(',',$skillArr);
            }

            if($request->has('job_id')){
                $job = PostJob::updateOrCreate(
                    $param,
                    $param2
                );
            } else {
                $param2[ "user_id" ] = $user_id;
                $param2[ "job_unique_id" ] = $job_unique_id;
                $job = PostJob::create(
                    $param2
                );
            }

            if(!empty($skillArr)){
                $insertedSkills = JobSkill::where("job_id",$job->id)->get()->toArray();
                $insertedSkills = collect($insertedSkills);
                $insertedSkills = $insertedSkills->pluck('key_skill_id')->toArray();
                $deleteJobSkills = array_diff($insertedSkills, $skillArr);
                $newinsertJobSkills = array_diff($skillArr, $insertedSkills);
                JobSkill::where(["job_id" => $job->id])->whereIn('key_skill_id',$deleteJobSkills)->delete();
                $newRecord = [];
                foreach($newinsertJobSkills as $new){
                    $newRecord[] = [
                        'key_skill_id' => $new,
                        'job_id' => $job->id,
                    ];
                }
                if(!empty($newRecord))
                    JobSkill::insert($newRecord);
            }

            if(isset($request->job_status)){
                $job_status = config('constant.job_status.Pending');
                if(($job->job_status == config('constant.job_status.Rejected') ? '' : $request->job_status != config('constant.job_status.Pending')) || $request->job_status != config('constant.job_status.Active')){
                    $job_status = $request->job_status;
                }
                $job->job_status = $job_status;
                $job->save();
            }

            
            if($job){
                DB::commit();
                $message = 'Your job has been saved successfully';
                $responseData['status'] = 1;
                $responseData['redirect'] = route('job.my-jobs');
                $responseData['message'] = $message;
                Session::flash('success', $responseData['message']);
            } else {
                DB::rollback();
                $responseData['message'] = trans('common.something_went_wrong');
                Session::flash('success', $responseData['message']);
            }
            return $this->commonResponse($responseData, 200);

        } catch(Exception $e){
            Log::emergency('job controller save Exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            $code = ($e->getCode() != '')?$e->getCode():500;
            $responseData['message'] = trans('common.something_went_wrong');
            return $this->commonResponse($responseData, $code);
        }
    }
    
}
