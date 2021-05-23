<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;

class BlogController extends Controller
{
    //View Dance/Music Type Listing Page
    public function index(){
        $blog = Blog::orderBy('created_at','desc')->get();
        return view('admin.blog.index',compact('blog'));
    }

    //Filter the dance and music types
    public function ajaxData(Request $request){
        // dd($request->all());

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = Blog::orderBy('id','desc');
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }

        $data = datatables()->of($Query)
        ->addColumn('title', function ($Query) {
            return $Query->title;
        })
        ->addColumn('src', function ($Query) {
            $icon = Helper::images(config('constant.blog_url')).$Query->src;
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
            $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_blog openBlogPopoup' data-title = '".$Query->title."' data-short_description = '".$Query->short_description."' data-description = '".$Query->description."' data-src ='".$Query->src."' data-id = '".$Query->id."' data-author_by = '".$Query->author_by."'  data-published_at = '".$Query->published_at."' ><i class='icon-pencil7 mr-3 text-primary edit_business_category'></i></a>&nbsp;&nbsp;";
            $action_link .= "<a href='javascript:;' class='blog_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";
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
            $blogData = [
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'status' => $status,
                'published_at' => $request->published_at,
                'author_by' => $request->author_by
            ];

            if($request->id){
                $blogData['updated_by'] = Auth::id();
            } else {
                $blogData['created_by'] = Auth::id();
            }

            $fileName = $request->id;
            if($request->hasFile('src')){
                $fileName = 'Img-' . time() . '.' . $image->getClientOriginalExtension();
                Helper::uploaddynamicFile(config('constant.blog_url'), $fileName,$image);
                $blogData['src'] = $fileName;

                if(isset($request->old_src)){
                    Helper::checkFileExists(config('constant.blog_url') . $request->old_src, true, true);
                }
            }
            Blog::updateOrCreate(['id' => $request->id], $blogData);
            DB::commit();
            if($request->id){
                return redirect()->route('blog.index')->with('success','Blog has been updated Successfully');
            }
            return redirect()->route('blog.index')->with('success','Blog has been added Successfully');
        } catch(Exception $e){
            \Log::info($e);
            DB::rollback();
            return response()->json(['status' => 0,'message' =>'Something Went Wrong']);
        }
    }

    public function edit($id){
        $blog = Blog::where('id',$id)->first();
        return view('admin.blog.index',compact('blog'));
    }

    public function destroy($id){
        if(isset($id)){
            DB::beginTransaction();
            try{
                $user = Auth::user();
                $blog = Blog::find($id);
                $blog->delete();
                $blog->deleted_by = $user->id;
                $blog->save();
                DB::commit();
                return array('status' => '200', 'msg_success' => 'Blog has been deleted Successfully');
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
            $blog = Blog::findOrFail($request->id);
            if ($blog->status == 0) {
                $blog->status = '1';
                $blog->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Blog has been activated successfully");
            } else {
                $blog->status = '0';
                $blog->update();
                DB::commit();
                return array('status' => '200', 'msg_success' => "Blog has been inactivated successfully");
            }
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
