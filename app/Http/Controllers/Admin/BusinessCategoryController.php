<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessCategory;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class BusinessCategoryController extends Controller
{
    //View Dance/Music Type Listing Page
    public function index(){
        $category = BusinessCategory::orderBy('created_at','desc')->get();
        return view('admin.business-category.index',compact('category'));
    }

    //Filter the dance and music types
    public function ajaxData(Request $request){
        // dd($request->all());

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = BusinessCategory::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('src', function ($Query) {
            $icon = $Query->src; //Helper::images(config('constant.dance_type_url')).
            return "<a class='fancy-pop-image' data-fancybox='images".$Query->id."'  href='".$icon."'><img class='custom-image' src='".$icon."'></a>";
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
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_dance_type openDanceTypePopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_dance_type'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='dancetype_deleted' data-id='".$Query->id . "' data-active='2' data-inuse='".$Query->getUserDanceType."' data-challengedancetype='".$Query->challengeDanceType."'><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status','src'])
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
            BusinessCategory::updateOrCreate(['id' => $request->id], $danceMusicTypes);
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
        $danceType = BusinessCategory::where('id',$id)->first();
        return view('admin.dancemusic.index',compact('danceType'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $DanceTypes = BusinessCategory::find($id);
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
            $danceType = BusinessCategory::findOrFail($request->id);
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
