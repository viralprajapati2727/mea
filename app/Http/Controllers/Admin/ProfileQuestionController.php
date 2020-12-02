<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfileQuestion;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class ProfileQuestionController extends Controller
{
    public function index(){
        $question = ProfileQuestion::orderBy('created_at','desc')->get();
        return view('admin.profile-question.index',compact('question'));
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = ProfileQuestion::orderBy('id','desc');
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
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_question openQuestionPopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_question'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='question_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }


    //Add question
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $questionData = [
                'title' => $request->title,
                'status' => $status,
            ];

            if($request->id){
                $questionData['updated_by'] = Auth::id();
            } else {
                $questionData['created_by'] = Auth::id();
            }

            ProfileQuestion::updateOrCreate(['id' => $request->id], $questionData);
            DB::commit();
            if($request->id){
                return redirect()->route('profile-question.index')->with('success','Question has been updated Successfully');
            }
            return redirect()->route('profile-question.index')->with('success','Question has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $question = ProfileQuestion::where('id',$id)->first();
        return view('admin.profile-question.index',compact('question'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $question = ProfileQuestion::find($id);
                $question->delete();
                $question->deleted_by = $user->id;
                $question->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Question has been deleted Successfully');
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
            $question = ProfileQuestion::findOrFail($request->id);
            if ($question->status == 0) {
                $question->status = '1';
                $question->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Question has been activated successfully");
            } else {
                $question->status = '0';
                $question->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Question has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
