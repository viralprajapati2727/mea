<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Illuminate\Support\Str;
use Validator;
use App\Models\Tags;
use App\Models\Community;
use App\Models\CommunityTags;
use App\Models\CommunityLikes;
use App\Models\QuestionCategory;

class CommunityController extends Controller{
    
    public function index(Request $request){

        $categories = QuestionCategory::select('id','title','status')->where('deleted_at',null)->where('status',1)->orderBy('id','DESC')->get();
        $tags = Tags::where('status',1)->select('title')->get()->pluck('title');
        
        return view('admin.community.index',compact('categories','tags'));

    }

    public function ajaxData(Request $request){
        // dd($request->all());

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }
        // $questions = Community::with('communityTags')->where('user_id',Auth::id())->where('deleted_at',null)->where('status',1);

        // if($request->search != null && $request->search != ""){
        //     $questions = $questions->where('title','like','%'.$request->search.'%');
        // }
        // if($request->category != null && $request->category != ""){
        //     $questions = $questions->where('question_category_id',$request->category);
        // }

        // $questions = $questions->orderBy('id','DESC')->get();
        
        $Query = Community::with('communityTags')->where('deleted_at',null)->where('status',1);
        if(!empty($keyword)){
            $Query->where('title','like','%'.$keyword.'%');
        }
        $Query = $Query->orderBy('id','DESC')->get();
        
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
            $slug = $Query->slug;
            $details_link = route('question.details',$slug) ;

            // $action_link .= "<a href='.add_modal' data-backdrop='static' data-keyboard='false' data-toggle ='modal' class='edit_business_category openCategoryPopoup' data-title = '".$Query->title."' data-src ='".$Query->src."' data-id = '".$Query->id."' ><i class='icon-pencil7 mr-3 text-primary edit_business_category'></i></a>&nbsp;&nbsp;";
            // $action_link .= "<a href='javascript:;' class='category_deleted' data-id='".$Query->id . "' data-active='2' data-inuse=''><i class='icon-trash mr-3 text-danger'></i></a>";

            $action_link .= "<a href=".$details_link." > <i class='icon-eye'></i> view </a>";

            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }


    public function detail($question_id=null){
        
        $question = Community::with('communityTags')->with('communityCategory')->with('communityLikes')->where('slug',$question_id)->select('id', 'slug','user_id', 'title', 'question_category_id', 'description', 'tags', 'views', 'created_at', "other_category")->first();

        return view('admin.community.details',compact('question'));
    }

}
