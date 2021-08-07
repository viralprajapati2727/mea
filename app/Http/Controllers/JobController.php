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
use App\Models\BusinessCategory;
use App\Models\JobApplied;



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
            $business_categories = BusinessCategory::select('id','title','status')->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();
            $skills = KeySkill::select('title As label')->get()->toArray();

            if(!empty($job_unique_id) && !is_null($job_unique_id)){
                $job = Helper::getJobData(Auth::id(),$job_unique_id,null,false,null);
            }
			return view('job.fill-job',compact('jobtitles','currencies','job','skills','business_categories'));
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
        // return $request;
        try{
            $is_pending_job = $is_paid = $is_find_team_member = 0;
            $user_id = Auth::id();

            $job_unique_id = PostJob::generateBarcodeNumber();

            if($request->has('job_id')){
                $param[ "id" ] = $request->job_id;
            }

            if(isset($request->is_paid)){
                $is_paid = 1;
            }

            if($request->job_type == 1){ //postjob
                $param2 = ["job_type" => $request->job_type, "job_title_id" => $request->job_title_id, "other_job_title" => $request->other_job_title, "business_category_id" => $request->business_category_id, "job_type_id" => $request->job_type_id, "currency_id" => $request->currency_id,
                "salary_type_id" => $request->salary_type_id, "min_salary" => $request->min_salary, "max_salary" => $request->max_salary, "job_start_time" => $request->job_start_time,"job_end_time" => $request->job_end_time, "time_zone" => $request->time_zone,
                "is_paid" => $is_paid,"description" => $request->description,"location" => $request->location,"created_by" => $user_id, "updated_by" => $user_id];

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

                /*
                 * save user shift data
                 */
                if(!empty($request->day_shift) || !empty($request->night_shift)){
                    $job->jobShift()->delete();
                    
                    // foreach ($request->shift as $key => $shift) {
                    //     $jobShift[] = ['job_id' => $job->id, 'shift_id' => $key, 'shift_val' => $shift];
                    // }

                    foreach (config('constant.SHIFT') as $key => $shift) {
                        
                        $dayShiftVal = 0;
                        $nightShiftVal = 0;
                        if(!empty($request->day_shift[$key]) && $request->day_shift[$key] != null) {
                            $dayShiftVal = 1;
                        }

                        if(!empty($request->night_shift[$key]) && $request->night_shift[$key] != null) {
                            $nightShiftVal = 1;
                        }

                        $jobShift[] = [
                            'job_id' => $job->id, 
                            'shift_id' => $key,
                            'day_shift_val' => $dayShiftVal,
                            'night_shift_val' => $nightShiftVal
                        ];
                    }
                    
                    $job->jobShift()->insert($jobShift);
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

                $message_text = "job";
            } else {//postrequest
                if(isset($request->is_find_team_member)){
                    $is_find_team_member = 1;
                }

                $param2 = ["job_type" => $request->job_type, "other_job_title" => $request->other_job_title, "is_find_team_member" => $request->is_find_team_member,
                "find_team_member_text" => $request->find_team_member_text, "description" => $request->r_description,"location" => $request->location,"created_by" => $user_id, "updated_by" => $user_id];

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

                if(isset($request->job_status)){
                    $job_status = config('constant.job_status.Pending');
                    if(($job->job_status == config('constant.job_status.Rejected') ? '' : $request->job_status != config('constant.job_status.Pending')) || $request->job_status != config('constant.job_status.Active')){
                        $job_status = $request->job_status;
                    }
                    $job->job_status = $job_status;
                    $job->save();
                }

                $message_text = "request";
            }

            
            if($job){
                DB::commit();
                $message = 'Your '.$message_text.' has been saved successfully';
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
    public function searchJob(){
        $business_categories = BusinessCategory::select('id','title','src','status')->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();

        $recentJobs = Helper::getRecentJobs();
        return view('job.search-job',compact('business_categories','recentJobs'));
    }
    public function globalSearch(){
        $params = [];
        if (isset($_GET['category']) != '' && !empty($_GET['category'])) {
			foreach ($_GET['category'] as $t) {
				$category[] = $t;
			}
			$params['category'] = $category;
        }
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
			$keyword = $_GET['keyword'];
			$params['keyword'] = $keyword;
        }

        if (isset($_GET['title']) && $_GET['title'] != '') {
			foreach ($_GET['title'] as $t) {
				$title[] = $t;
			}
			$params['title'] = $title;
        }
        
        if (isset($_GET['city']) != '' && !empty($_GET['city'])) {
			foreach ($_GET['city'] as $t) {
				$city[] = $t;
			}
			$params['city'] = $city;
        }
        
        $paginate = [];
        $paginate['page_size'] = config('constant.rpp');
        
        $jobs = Helper::globalSearchJobs($paginate, $params);

        $jobtitles = JobTitle::where('deleted_at',null)->get();
        $business_categories = BusinessCategory::select('id','title','src','status')->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();

        return view('job.global-jobs', compact('jobs','params','jobtitles','business_categories'));
    }
    public function detail($id){
        try{
            $job = null;
            
            if(empty($id)){
                return redirect()->route('index');
            }

            if(!empty($id) && !is_null($id)){
                $job = Helper::getJobData(null,$id,null,false,null);
            }
            if(empty($job)){
                return redirect()->route('index');
            }
            PostJob::where('id',$job->id)->update([
                'job_count'=> $job->job_count + 1
            ]);

			return view('job.job-detail',compact('job'));
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }
    public function checkAppliedJob(Request $request){
        // echo "<pre>"; print_r($request->all()); exit;
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        try{

            if(empty($request->job_id)){
                return $this->commonResponse($responseData, 200);
            }
            $user_id = Auth::id();

            $data = Helper::checkJobApplied($request->job_id, $user_id);

            $job_applied_id = 0;
            if(!empty($data) && $data->count() > 0){
                $job_applied_id = $data->id;
                $responseData['data']['additional_information'] = $data->additional_information;
            }

            $responseData['status'] = 200;
            $responseData['data']['job_applied_id'] = $job_applied_id;
            
            return $this->commonResponse($responseData, 200);

        }catch(Exception $e){
            $responseData['status'] = 0;
            return $this->commonResponse($responseData, 200);
        }
    }
    public function applyJob(Request $request){
        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];
        try{

            if(empty($request->job_id)){
                return $this->commonResponse($responseData, 200);
            }
            $user_id = Auth::id();

            $data = Helper::checkJobApplied($request->job_id, $user_id);

            $job_applied_id = 0;
            if(!empty($data) && $data->count() > 0){
                $job_applied_id = $data->id;
            }

            $param = [ "user_id" => $user_id ];
            $param2 = ['job_id' => $request->job_id, 'job_applied_status_id' => 0, "additional_information" => $request->description];

            $jobApplied = JobApplied::updateOrCreate(
                $param,
                $param2
            );

            $responseData['status'] = 200;
            $responseData['message'] = 'You have successfully applied';

            Session::flash('success', $responseData['message']);
            return $this->commonResponse($responseData, 200);

        }catch(Exception $e){
            $responseData['status'] = 0;
            return $this->commonResponse($responseData, 200);
        }
    }
    public function viewApplicant($job_id = null){
        try{

            if(!isset($job_id) || empty($job_id)){
                return redirect()->route('job.my-jobs')->with('error', 'Something went wrong');
            }

            $params = [];
            if (isset($_GET['status']) != '') {
                $params['status'] = $_GET['status'];
            }

            if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                $keyword = $_GET['keyword'];
                $params['keyword'] = $keyword;
            }

            $applicants = Helper::applyJobData($job_id, 10, $params);

            return view('job.applicant',compact('applicants','job_id','params'));

        } catch(Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
    public function changeApplicantStatus(Request $request){
        DB::beginTransaction();
        try{
            $applyJob = JobApplied::where(['id' => $request->id])->first();
            // if($request->status > $applyJob->job_applied_status_id){
                $applyJob->job_applied_status_id = $request->status;
                $applyJob->update();
                $msg = 'Applicant status has been changed successfully';
                // $email_id = null;
                // if($request->status == 4){
                //     $email_id = 13;
                // } else if($request->status == 3){
                //     $email_id = 14;
                // } else if($request->status == 5){
                //     $email_id = 15;
                // } else if($request->status == 1){
                //     $email_id = 12;
                // } else if($request->status == 0){
                //     $email_id = 17;
                // } else if($request->status == 2){
                //     $email_id = 18;
                // }
                // if(!is_null($email_id)){
                //     $mail_data = ['email_id' => $email_id, 'user_id' => $applyJob->user_id, 'job_apply_id' => $applyJob->id];
                //     dispatch(new DynamicEmail($mail_data));
                // }

                DB::commit();
                return array('status' => '200', 'msg_success' => trans($msg));
            // }else{
            //     return array('status' => '0', 'msg_fail' => trans('app.Something_went_wrong'));
            // }
        } catch(Exception $e){
            DB::rollback();
            return array('status' => '0', 'msg_fail' => trans('app.Something_went_wrong'));
        }
    }

    public function appliedJob(Request $request) {
        $jobs = JobApplied::with(['user', 'job'])->where('user_id',Auth::id())->paginate(10);

        // dd($jobs);

        return view('job.applied-jobs',compact('jobs'));
    }

    public function cancelJob($id) {

        $responseData = array();
        $responseData['status'] = 0;
        $responseData['message'] = '';
        $responseData['errors'] = array();
        $responseData['data'] = [];

        if ($id) {
            JobApplied::where('id', $id)->where('user_id',Auth::id())->delete();
        }

        $responseData['status'] = 200;
        $responseData['message'] = 'Applied job cancelled successfully';

        Session::flash('success', $responseData['message']);
        
        return $this->commonResponse($responseData, 200);
    }
}
