<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Helper;
use DB;
use App\Models\ProfileQuestion;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.index');
    }

    //Filter the professional
    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = User::where('type',config('constant.USER.TYPE.SIMPLE_USER'))->select('id','name','email','logo','is_profile_filled','is_active','slug')->with([
            'userProfile' => function($query){
            $query->select('id','user_id','city','gender');
        }])
        ->orderBy('created_at','desc');

        if($request->status != ""){
            $status = $request->status;
            $Query->where(function ($query1) use($status) {
                $query1->where('is_active',$status);
            });
        }
        if(!empty($keyword)){
            $Query->where(function ($query1) use($keyword) {
                $query1->where('name','like','%'.$keyword.'%');
                $query1->orWhere('email','like','%'.$keyword.'%');
                $query1->orWhereHas('userProfile',function($query2) use ($keyword){
                    $query2->where('city','like','%'.$keyword.'%');
                });
            });
        }


        $data = datatables()->of($Query)
            ->addColumn('name', function ($Query) {
                $text = "<a class='text-primary' href='".route('admin.user.details',['slug' => $Query->slug])."'>".$Query->name."</a>";
                return $text;
            })
            ->addColumn('email', function ($Query) {
                return $Query->email;
            })
            ->addColumn('gender', function ($Query) {
                return $Query->userProfile->gender ?? "-";
            })
            ->addColumn('location', function ($Query) {
                return $Query->userProfile->city ?? "-";
            })
            ->addColumn('status', function ($Query) {
                $status = array_search($Query->is_active,config('constant.USER.STATUS'));

                $statusArr = [0 => 'info', 1 => 'success', 2 => 'danger'];
                $is_active = 1;
                if($Query->is_active == 1){
                    $is_active = 2;
                }
                $class="change-status";
                if($Query->is_active == 0){
                    $is_active = 1;
                }
                $text = "<span class='badge badge-".$statusArr[$Query->is_active]."'><a href='javascript:;' class=".$class." data-active=".$is_active." data-id='".$Query->id."'>".$status."</a></span>";
                return $text;
            })
            ->addColumn('action', function ($Query) {
                $action_link = '';
                if($Query->is_profile_filled){
                    $action_link .= "<a href='".route('admin.user.details',['slug'=>$Query->slug])."' title='View Profile' class='view'><i class='icon-eye mr-3 text-primary'></i></a>";
                }

                $action_link .= "<a href='javascript:;' title='Remove User' class='remove_user' data-status='".$Query->is_active."' data-id='".$Query->id . "'><i class='icon-trash
                mr-3 text-danger'></i></a>";
            
                return $action_link;
            })
            ->rawColumns(['action','status','name'])
            ->make(true);
        return $data;
    }

    //VIEW PROFESSIONAL DETAILS ADMIN SIDE
    public function viewDetails($slug){
        try{

            if(empty($slug)){
                return redirect()->route('index');
            }
    
            $profile = Helper::userProfile($slug);
            $questions = ProfileQuestion::where('deleted_at',null)->get();
            
            if(empty($profile)){
                return redirect()->route('admin.user.index')->with('error', 'No user details found!');
            }

            return view('admin.user.detail',compact('profile','questions'));

        } catch(Exception $e){
            \Log::info($e);
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }
}
