<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostJob;
use App\Models\JobTitle;
use DB, Log;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class JobController extends Controller
{
    public function pendingJob(){
        $jobtitles = JobTitle::where('deleted_at',null)->get();
        return view('admin.job.pending',compact('jobtitles'));
    }
    
    public function pendingAjaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = PostJob::select('id','job_title_id','other_job_title','job_type','job_type_id','job_unique_id','currency_id','min_salary','max_salary','job_start_time','job_end_time','job_status','created_at')->orderBy('id','desc');
        $Query->whereIn('job_status',[0,2]);

        if($request->has('job_title_id') && $request->job_title_id != null){
            $Query->where('job_title_id',$request->job_title_id);
        }
        if($request->has('job_type_id') && $request->job_type_id != null){
            $Query->where('job_type_id',$request->job_type_id);
        }
        if(!empty($request->created_at_from) && !empty($request->created_at_to)){
            $posted_date_from = date('Y-m-d',strtotime($request->created_at_from));
            $posted_date_to = date('Y-m-d',strtotime($request->created_at_to));
            $Query->whereBetween('created_at',[$posted_date_from, $posted_date_to]);
        }

        $data = datatables()->of($Query)
        ->addColumn('jobtitle', function ($Query) {
            $title = $Query->job_title_id > 0 ? $Query->jobTitle->title : $Query->other_job_title;
            return "<a href='".route('admin.job.detail',['pending',$Query->job_unique_id])."' class='detail'>".$title."</a>&nbsp;&nbsp;";
        })
        ->addColumn('jobtype', function ($Query) {
            if($Query->job_type == 1){
                return config('constant.job_type')[$Query->job_type_id];
            }
            return "-";
        })
        ->addColumn('job_type', function ($Query) {
            if($Query->job_type == 1){
                return "Post Job";
            }
            return "Post Request";
        })
        ->addColumn('salary_range', function ($Query) {
            if($Query->is_paid){
                return $Query->currency->code ." ". $Query->min_salary." - ".$Query->currency->code ." ". $Query->max_salary;
            }
            return "-";
        })
        ->addColumn('job_time', function ($Query) {
            return $Query->job_start_time." - ".$Query->job_end_time;
        })
        ->addColumn('created_at', function ($Query) {
            return date('d-m-Y', strtotime($Query->created_at));
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            if($Query->job_status == 2){
                $action_link .= "<span class='translation-status'>";
                $action_link .= "<a href='javascript:;' class='approve-reject' data-id='".$Query->id."' data-active='1'>APPROVE</a>&nbsp;&nbsp;";
                $action_link .= "<i class='icon-lock5 mr-3 icon-1x text-danger'></i>";
                $action_link .= "</span>";
            } else {
                $action_link .= "<span class='translation-status'>";
                $action_link .= "<a href='javascript:;' class='approve-reject' data-id='".$Query->id."' data-active='1'>APPROVE</a>&nbsp;/&nbsp;";
                $action_link .= "<a href='javascript:;' class='approve-reject' data-id='".$Query->id."' data-active='2'>REJECT</a>&nbsp;&nbsp;";
                $action_link .= "</span>";
            }
            $action_link .= "<a href='javascript:;' class='deleted' data-id='".$Query->id . "'><i class='icon-trash text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','jobtitle'])
        ->make(true);
        return $data;
    }
    public function jobStatus(Request $request) {
        $admin_id = Auth::user()->id;
        try {
            $job = PostJob::whereId($request->id)->first();
            $job->job_status = $request->status;
            $job->update();

            $statusText = "Approved";
            if($request->status == 2){
                $statusText = "Rejected";
            }


            
            return array('status' => '200', 'msg_success' => "Job has been ".$statusText." successfully");
		} catch (Exception $e) {
			Log::info($e);
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
		}
		exit;
    }
    public function activeJob(){
        $jobtitles = JobTitle::where('deleted_at',null)->get();
        return view('admin.job.active',compact('jobtitles'));
    }
    
    public function activeAjaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = PostJob::select('id','job_title_id','other_job_title','job_type','job_type_id','job_unique_id','currency_id','min_salary','max_salary','job_start_time','job_end_time','job_status','created_at')->orderBy('id','desc');
        $Query->whereIn('job_status',[1]);

        if($request->has('job_title_id') && $request->job_title_id != null){
            $Query->where('job_title_id',$request->job_title_id);
        }
        if($request->has('job_type_id') && $request->job_type_id != null){
            $Query->where('job_type_id',$request->job_type_id);
        }
        if(!empty($request->created_at_from) && !empty($request->created_at_to)){
            $posted_date_from = date('Y-m-d',strtotime($request->created_at_from));
            $posted_date_to = date('Y-m-d',strtotime($request->created_at_to));
            $Query->whereBetween('created_at',[$posted_date_from, $posted_date_to]);
        }

        $data = datatables()->of($Query)
        ->addColumn('jobtitle', function ($Query) {
            $title = $Query->job_title_id > 0 ? $Query->jobTitle->title : $Query->other_job_title;
            return "<a href='".route('admin.job.detail',['active',$Query->job_unique_id])."' class='detail'>".$title."</a>&nbsp;&nbsp;";
        })
        ->addColumn('jobtype', function ($Query) {
            if($Query->job_type == 1){
                return config('constant.job_type')[$Query->job_type_id];
            }
            return "-";
        })
        ->addColumn('job_type', function ($Query) {
            if($Query->job_type == 1){
                return "Post Job";
            }
            return "Post Request";
        })
        ->addColumn('salary_range', function ($Query) {
            if($Query->is_paid){
                return $Query->currency->code ." ". $Query->min_salary." - ".$Query->currency->code ." ". $Query->max_salary;
            }
            return "-";
        })
        ->addColumn('job_time', function ($Query) {
            return $Query->job_start_time." - ".$Query->job_end_time;
        })
        ->addColumn('created_at', function ($Query) {
            return date('d-m-Y', strtotime($Query->created_at));
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='javascript:;' class='deleted' data-id='".$Query->id . "'><i class='icon-trash text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','jobtitle'])
        ->make(true);
        return $data;
    }
    public function detail($status, $id){
        $job = Helper::getJobData(null,$id,null,false,null);

        if(empty($job)){
            return redirect()->route('admin.job.'.$status)->with('error','No data found!');
        }

        return view('admin.job.detail',compact('status','job'));
    }
}
