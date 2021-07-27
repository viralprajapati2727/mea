<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use DB, Log;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class TopicController extends Controller
{
    public function index(){
        return view('admin.topic.index');
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Topic::whereNull('parent_id')->orderBy('topic_order','asc');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('topic_order', function ($Query) {
            return $Query->topic_order ? $Query->topic_order : "-" ;
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
            $action_link .= "<a href='".route('sub-topic.index',['id' => $Query->id])."' data-backdrop='static' class='' ><i class='icon-eye mr-3 text-primary'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_dance_type openTopicPopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' data-topic_order = '".  $Query->topic_order. "' ><i class='icon-pencil7 mr-3 text-primary edit_jobtitle'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='topic_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
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

            $maxId = Topic::whereNull('parent_id')->max('topic_order');
            $topic_order = $request->topic_order;
            if(empty($request->topic_order) || $request->topic_order < 1 ){
                $topic_order = $maxId + 1;
            }

            $topicData = [
                'title' => $request->title,
                'status' => $status,
                'topic_order' => $topic_order
            ];

            $topic = Topic::updateOrCreate(['id' => $request->id], $topicData);
 
            DB::commit();
            if($request->id){
                return redirect()->route('topic.index')->with('success','Topic has been updated Successfully');
            }
            return redirect()->route('topic.index')->with('success','Topic has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $topic = Topic::where('id',$id)->first();
        return view('admin.topic.index',compact('topic'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $subTopic = Topic::where('parent_id', $id)->delete();
                $topic = Topic::find($id);
                $topic->delete();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Topic has been deleted Successfully');
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
            $topic = Topic::findOrFail($request->id);
            if ($topic->status == 0) {
                $topic->status = '1';
                $topic->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Topic has been activated successfully");
            } else {
                $topic->status = '0';
                $topic->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Topic has been inactivated successfully");
            }
        } catch (Exception $e) {
        }
    }

    //Check category unique
    public function checkUniqueTopic(Request $request){
        try{
            $title = $request->title;
        
            $exists = Topic::where('title', $title)->where('id','!=', $request->id)->exists();

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
