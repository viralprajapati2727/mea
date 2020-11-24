<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use DB;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\SendMailController;

class StaffController extends Controller
{
    public function index(){
        return view('admin.staff.index');

    }

    public function ajaxData(Request $request){
        $keyword = "";
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
        }
        if(!empty($request->status)){
            $status = $request->status;
        }
        
        $Query = User::where('type',config('constant.USER.TYPE.STAFF'))->select('id','name','email','nick_name','is_active','suspended_till','is_profile_filled')->orderBy('created_at','desc');
        if(!empty($keyword)){
            $Query->where(function($query) use ($keyword){
                $query->where('name','like','%'.$keyword.'%');
                $query->orWhere('email','like','%'.$keyword.'%');
            });
        }
        if(!empty($status)){
            $Query->where(function($query) use ($status){
                $query->where('is_active',$status);
            });
        }

        $sr_no = $request->start;
        $data = datatables()->of($Query)
        ->addColumn('sr_no', function ($Query) use (&$sr_no) {
            $sr_no++;
            return $sr_no;
        })
        ->addColumn('name', function ($Query) {
            return $Query->name;
        })
        ->addColumn('email', function ($Query) {
            return $Query->email;
        })
        ->addColumn('status', function ($Query) {
            $status = array_search($Query->is_active,config('constant.USER.STATUS'));
            // $status = strtolower($status);
            // $status = str_replace('_',' ',$status);
            // return ucfirst($status);

            $statusArr = [0 => 'info', 1 => 'success', 2 => 'secondary', 3 => 'danger', 4 => 'danger'];
            $text = "<span class='custom-badge badge badge-".$statusArr[$Query->is_active]."'>".$status."</span>";
            $till = '';
            if($Query->is_active == 4 && !empty($Query->suspended_till)){ //suspended by admin
                $till = "<br/>till ".$Query->suspended_till;
            }
            $text .= "<span class='suspend-till'>".$till."</span>";
            return  $text;

        })
        ->addColumn('action', function ($Query) {
            $action_link = "";
            $action_link .= "<a href='".route('admin.staff.edit',$Query->id)."' class='edit'><i class='icon-pencil7 mr-3 text-primary'></i></a>&nbsp;&nbsp;";
            if($Query->is_active == 1){
                $action_link .= "<a href='javascript:;' class='activate_user user-status' title='Suspend' data-status='".$Query->is_active."' data-id='".$Query->id . "'><i class='icon-user-block
                mr-3 text-danger'></i></a>";
            } else {
                $action_link .= "<a href='javascript:;' class='suspend_user user-status' title='Activate' data-status='".$Query->is_active."' data-id='".$Query->id . "' data-suspend_date='".$Query->suspended_till."'><i class='icon-user-check
                mr-3 text-success'></i></a>";
            }
            $action_link .= "<a href='javascript:;' class='staff-deleted' data-id='".$Query->id . "'><i class='icon-trash mr-3 text-danger'></i></a>";
            return $action_link;
        })
        ->rawColumns(['action','status'])
        ->make(true);
        return $data;
    }
    
    public function create()
    {
        $id = null;
        $pvsIds = [];
        return view('admin.staff.create',compact('id','pvsIds'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $password = $request->password;
        $userData = User::orderBy('created_at', 'desc')->first();
        $uniqueId = 0;
        try{
            if(empty($request->user_id)){
                $validator = Validator::make($request->all(), [
                    'name' => "required",
                    'email' => "required|unique:users,email,NULL,id,deleted_at,NULL|email",
                    'password' => "required|min:6",
                    'privilege' => "required",
                ],[
                    'name.required' => 'Please enter name',
                    'email.required' => 'Please enter your e-mail address',
                    'email.email' => 'Please enter a valid email address',
                    'email.unique' => 'This email address already exists',
                    'password.required' => 'Please enter a password',
                    'password.min' => 'At least 6 characters required',
                    'privilege.required' => 'Please choose any Access Permission',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'privilege' => "required",
                ],[
                    'privilege.required' => 'Please choose any Access Permission',
                ]);
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if(empty($request->user_id)){
                $model = new User();
                $model->name = $request->name;
                $model->email = $request->email;
                $model->password = Hash::make($request->password);
                $model->type = config('constant.USER.TYPE.STAFF');
                $model->is_active = 1;
                $model ->email_verified_at = Carbon::today();

                $model->save();
                $user = $model;
                $user_id = $model->id;
            } else {
                $user = User::find($request->user_id);
                $user_id = $user->id;
            }

            $user->staffPrivilege()->delete();

            if($request->has('privilege')){
                foreach ($request->privilege as $key => $privilege) {
                    $staffPrivilege = $user->staffPrivilege()->updateOrCreate(
                        ["user_id" => $user_id, "access_id" => $privilege]
                    );
                }
            }
            DB::commit();

            if(empty($request->user_id)){
                SendMailController::dynamicEmail([
                    'email_id' => 23,
                    'user_id' => 1,
                    'user_name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                ]);
            }

            return redirect()->route('admin.staff.index')->with('success','Staff has been added successfully.');
        }catch(Exception $e){
            \Log::info($e);
            DB::rollback();
			return redirect()->back()->with('errors','Something Went Wrong');
        }
    }

    public function edit($id){
        $model = User :: with('staffPrivilege')->where(['type' => config('constant.USER.TYPE.STAFF'), 'id' => $id])->first();
        $pvs = collect($model->staffPrivilege);
        $pvsIds = $pvs->pluck('access_id')->toArray();
        return view('admin.staff.create',compact('id','model','pvsIds'));
    }
    
    public function checkUniqueEmail(Request $request){
        $exists = User::select('id','email','deleted_at')->where([['email',$request->email],['deleted_at',null]])->exists();
        if($exists){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function changeStatus(Request $request){
        try{
            DB::beginTransaction();
            $currentUser = User::where('id',$request->id)->first();
            $currentDate = Carbon::now();
            $nextWeek = $currentDate->addDays(7);
            $format = $nextWeek->format('d/m/Y');
            $nextWeek = ($request->flag == 4) ? $nextWeek->format('Y/m/d') : NULL;

            $currentUser = $currentUser->update([
                'is_active' => $request->flag,
                'suspended_till' => $nextWeek,
            ]);
            if($request->flag == 3){
                $msg = 'User has been Suspended Permanently';
            } else if($request->flag == 4){
                $msg = 'User has been Suspended for a '.$format;
            } else {
                $msg = 'User has been Activated Successfully';
            }
            DB::commit();
            return array('status' => 200,'msg_success' => $msg,'suspended_till' => $format,'flag' => $request->flag);
        } catch(Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return response()->json(['status' => 400,'msg_fail' => 'Something Went Wrong']);
        }
    }
    
    public function deleteStaffUser(Request $request){
		try{
			if($request->id){
				DB::beginTransaction();
				$user = User::where('id',$request->id)->first();
				if($user->is_active == 1){
					$user->delete();
					$user->is_active = 2;
					$user->save();
                    DB::commit();
					return array('status' => '200', 'msg_success' => trans('page.staff_deleted_successfully'));
				}
			}else{
				DB::rollback();
				return array('status' => '0', 'msg_fail' => trans('page.something_went_wrong'));
			}
		} catch(Exception $e){
			DB::rollback();
			return array('status' => '0', 'msg_fail' => trans('page.something_went_wrong'));
		}
	}
}
