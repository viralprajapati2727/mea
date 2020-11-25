<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionCategory;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class QuestionCategoryController extends Controller
{
    public function index(){
        $danceType = QuestionCategory::orderBy('created_at','desc')->get();
        return view('admin.question-category.index',compact('danceType'));
    }

    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = QuestionCategory::orderBy('id','desc');
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
            $action_link .= "<a href='javascript:;' class='edit_category' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_dance_type'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='deleted' data-id='".$Query->id . "' data-active='2'><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }


    //Add Dance/Music Type
    public function store(Request $request){
        DB::beginTransaction();
        try{
            $status = 0;
            if(isset($request->status)){
                $status = 1;
            }
            $image = $request->file('src');
            $danceMusicTypes = [
                'title' => $request->title,
                'status' => $status,
            ];
            $fileName = $request->id;
            if($request->hasFile('src')){
                $fileName = 'Img-' . time() . '.' . $image->getClientOriginalExtension();
                Helper::uploaddynamicFile(config('constant.dance_type_url'), $fileName,$image);
                $danceMusicTypes['src'] = $fileName;

                if(isset($request->old_src)){
                    Helper::checkFileExists(config('constant.dance_type_url') . $request->old_src, true, true);
                }
            }
            QuestionCategory::updateOrCreate(['id' => $request->id], $danceMusicTypes);
            DB::commit();
            if($request->id){
                return redirect()->route('dance-type.index')->with('success','Dance/Music Type has been updated Successfully');
            }
            return redirect()->route('dance-type.index')->with('success','Dance/Music Type has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $danceType = QuestionCategory::where('id',$id)->first();
        return view('admin.dancemusic.index',compact('danceType'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $DanceTypes = QuestionCategory::find($id);
                $DanceTypes->delete();
                $DanceTypes->deleted_by = $user->id;
                $DanceTypes->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Dance/Music Type has been deleted Successfully');
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
            $danceType = QuestionCategory::findOrFail($request->id);
            if ($danceType->status == 0) {
                $danceType->status = '1';
                $danceType->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "DanceType has been activated successfully");
            } else {
                $danceType->status = '0';
                $danceType->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "DanceType has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }

}
