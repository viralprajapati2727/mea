<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Helper;
use DB;

class EntrepreneurController extends Controller
{
    public function index(){
        return view('admin.entrepreneur.index');
    }

    //Filter the professional
    public function ajaxData(Request $request){

        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }

        $Query = User::where('type',config('constant.USER.TYPE.ENTREPRENEUR'))->select('id','name','email','logo','is_profile_filled','is_active')->with([
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
                $text = "<a class='text-primary' href='".route('admin.entrepreneur.details',$Query->slug)."'>".$Query->name."</a>";
                return $text;
            })
            ->addColumn('email', function ($Query) {
                return $Query->email;
            })
            ->addColumn('gender', function ($Query) {
                return $Query->userProfile->gender;
            })
            ->addColumn('location', function ($Query) {
                return $Query->userProfile->city;
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
                    $class="";
                }
                $text = "<span class='badge badge-".$statusArr[$Query->is_active]."'><a href='javascript:;' class=".$class." data-active=".$is_active." data-id='".$Query->id."'>".$status."</a></span>";
                return $text;
            })
            ->addColumn('action', function ($Query) {
                $action_link = '';
                if($Query->is_profile_filled){
                    $action_link .= "<a href='".route('admin.entrepreneur.details',$Query->slug)."' title='View Profile' class='view'><i class='icon-eye mr-3 text-primary'></i></a>";
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
    public function viewDetails($slug,$report = null){
        try{

            $where['active'] = true;
            $eventTypes = Helper::getEventTypes($where);
            $where['deleted'] = true;
            $danceTypes = Helper::getDanceMusicTypes($where);

            $details = User::where('slug',$slug)
            ->with(['userProfile:id,user_id,description,dob,gender,address,phone,country_id,city_id,total_wallet,wallet_unique_id,fb_link,web_link','userExpertise' => function($query){
                $query->with(['getProfessionalType' => function($query){
                    $query->select('id','title')->withTrashed();
                }]);
            },'userDanceMusicTypes' => function($query){
                $query->with(['getDanceType' => function($query){
                    $query->select('id','title')->withTrashed();
                }]);
            },'userFollowers' => function($query){
                $query->with('followers:id,name,slug,logo');
            },'userSettings:user_id,bank_account_number,bank_name,bank_ifsc_code,bank_country'])
            ->first();

            $id = User::where('slug',$slug)->select('id')->first();
            $count = UserFollower::where(['user_followers.user_id' => $id->id, 'users.deleted_at' => null,'users.is_active' => 1])->join('users', function($join) {
                $join->on('user_followers.followers_id', '=', 'users.id');
            })->count();
            $report = SpamReporting::where('id',$report)->first();

            //for staff manager professional wallet access
            $user = Auth::user();
            $is_wallet_acsess = 0;
            if($user->type == 5 && isset($user->staffPrivilege)){
                foreach ($user->staffPrivilege as $priv) {
                    $accessModule = $priv->access_id;
                    if($accessModule == 3.1){
                        $is_wallet_acsess = 1;
                    }
                }
            }else{
                if($user->type == 1){
                    $is_wallet_acsess = 1;
                }
            }


            return view('admin.professional.detail',compact('details','report','count','danceTypes','eventTypes','is_wallet_acsess'));

        } catch(Exception $e){
            \Log::info($e);
            return redirect()->back()->with('warning',$e->getMessage());
        }
    }
}
