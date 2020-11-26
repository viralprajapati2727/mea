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
            $icon = Helper::images(config('constant.business_category_url')).$Query->src;
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
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_business_category openCategoryPopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_business_category'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='category_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
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
            $categoryData = [
                'title' => $request->title,
                'status' => $status,
            ];
            $fileName = $request->id;
            if($request->hasFile('src')){
                $fileName = 'Img-' . time() . '.' . $image->getClientOriginalExtension();
                Helper::uploaddynamicFile(config('constant.business_category_url'), $fileName,$image);
                $categoryData['src'] = $fileName;

                if(isset($request->old_src)){
                    Helper::checkFileExists(config('constant.business_category_url') . $request->old_src, true, true);
                }
            }
            BusinessCategory::updateOrCreate(['id' => $request->id], $categoryData);
            DB::commit();
            if($request->id){
                return redirect()->route('business-category.index')->with('success','Business Category has been updated Successfully');
            }
            return redirect()->route('business-category.index')->with('success','Business Category has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $category = BusinessCategory::where('id',$id)->first();
        return view('admin.business-category.index',compact('category'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $category = BusinessCategory::find($id);
                $category->delete();
                $category->deleted_by = $user->id;
                $category->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Business Category has been deleted Successfully');
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
            $category = BusinessCategory::findOrFail($request->id);
            if ($category->status == 0) {
                $category->status = '1';
                $category->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Business Category has been activated successfully");
            } else {
                $category->status = '0';
                $category->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Business Category has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
    //Check category unique
    public function checkUniqueCategory(Request $request){
        try{
            $title = $request->title;
        
            $exists = BusinessCategory::where('title', $title)->where('id','!=', $request->id)->exists();

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
