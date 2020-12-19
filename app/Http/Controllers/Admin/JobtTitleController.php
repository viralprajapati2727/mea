<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobTitle;
use DB, Log;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class JobTitleController extends Controller
{
    public function index(){
        return view('admin.job-title.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = JobTitle::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('status', function ($Query) {
            $text = "<span class='badge badge-danger'><a href='javascript:;' class='type-status' data-active='1' data-id='".$Query->id."'>INACTIVE</a></span>";
            if($Query->status == 1){
                $text = "<span class='badge badge-success'><a href='javascript:;' class='type-status' data-active='0' data-id='".$Query->id."'>ACTIVE</a></span>";
            }
            return $text;
        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_dance_type openJobTitlePopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_jobtitle'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='jobtitle_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }


    //Add jobtitle
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $jobtitleData = [
                'title' => $request->title,
                'status' => $status,
            ];

            if($request->id){
                $jobtitleData['updated_by'] = Auth::id();
            } else {
                $jobtitleData['created_by'] = Auth::id();
            }

            JobTitle::updateOrCreate(['id' => $request->id], $jobtitleData);
            DB::commit();
            if($request->id){
                return redirect()->route('job-title.index')->with('success','Job Title has been updated Successfully');
            }
            return redirect()->route('job-title.index')->with('success','Job Title has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $jobtitle = JobTitle::where('id',$id)->first();
        return view('admin.job-title.index',compact('jobtitle'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $jobtitle = JobTitle::find($id);
                $jobtitle->delete();
                $jobtitle->deleted_by = $user->id;
                $jobtitle->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Job Title has been deleted Successfully');
            } catch(Exception $e){
                DB::rollback();
                return array('status' => '0', 'msg_fail' => 'Something went wrong');
            }
        }
    }

    //Change Status
    public function changeStatus(Request $request){
        DB::beginTransaction();
        try {
            $jobtitle = JobTitle::findOrFail($request->id);
            if ($jobtitle->status == 0) {
                $jobtitle->status = '1';
                $jobtitle->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Job Title has been activated successfully");
            } else {
                $jobtitle->status = '0';
                $jobtitle->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Job Title has been inactivated successfully");
            }
        } catch (Exception $e) {
        }
    }

    //Check category unique
    public function checkUniqueJobTitle(Request $request){
        try{
            $title = $request->title;
        
            $exists = JobTitle::where('title', $title)->where('id','!=', $request->id)->exists();

            if($exists){
                return "false";
            } else {
                return "true";
            }
            
        } catch(Exception $e){
            \Log::info($e);
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }
}
