<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Setting;
use App\Models\CandidateProfile;
use App\Models\WalletLog;
use App\Models\PaymentLog;
use App\Models\UserExpertise;
use App\Models\EventBooking;
use App\Models\EventManager;
use App\Models\EventBookingTicketType;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\Helper;

class AdminController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public $user;

	public function __construct() {
		$this->user = Auth::user();
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		if (Auth::check()){
			if($this->user->type == 1 || $this->user->type == 4){
				return redirect()->route('admin.index');
			}
		}
		return redirect()->route('index');
	}

	public function dashboard() {
		$from_date = $to_date = "";
		$from_date_temp = $to_date_temp = null;
		if(isset($_GET['from_date']) && !empty($_GET['from_date'])){
			$from_date = $_GET['from_date'];
			$from_date_temp = Carbon::createFromFormat('d/m/Y', $_GET['from_date'])->format('Y-m-d');
        }
        if(isset($_GET['to_date']) && !empty($_GET['to_date'])){
            $to_date = $_GET['to_date'];
            $to_date_temp = Carbon::createFromFormat('d/m/Y', $_GET['to_date'])->format('Y-m-d');
		}

		//dancer Count
		$users = User::where('type',config('constant.USER.TYPE.SIMPLE_USER'))->where('deleted_at',NULL)->select('*');

		if (isset($from_date_temp) && $from_date_temp != null && isset($to_date_temp) && $to_date_temp != null) {
			$users->whereDate('created_at', '>=', $from_date_temp);
            $users->whereDate('created_at', '<=', $to_date_temp);
		} elseif (isset($from_date_temp) && $from_date_temp != null) {
            $users->whereDate('created_at', $from_date_temp);
        } elseif (isset($to_date_temp) && $to_date_temp != null) {
            $users->whereDate('created_at', $to_date_temp);
		}
		$users = $users->count();

		//entrepreneurs Count
		$entrepreneurs = User::where('type',config('constant.USER.TYPE.ENTREPRENEUR'))->where('deleted_at',NULL)->select('*');

		if (isset($from_date_temp) && $from_date_temp != null && isset($to_date_temp) && $to_date_temp != null) {
			$entrepreneurs->whereDate('created_at', '>=', $from_date_temp);
            $entrepreneurs->whereDate('created_at', '<=', $to_date_temp);
		} elseif (isset($from_date_temp) && $from_date_temp != null) {
            $entrepreneurs->whereDate('created_at', $from_date_temp);
        } elseif (isset($to_date_temp) && $to_date_temp != null) {
            $entrepreneurs->whereDate('created_at', $to_date_temp);
		}
		$entrepreneurs = $entrepreneurs->count();

        return view('admin.dashboard',compact('users','entrepreneurs','from_date','to_date'));
	}

	public function changePassword() {
		return view('admin.change_password');
	}

	public function updatePassword(Request $request) {
		try {
			$validator = Validator::make($request->all(), [
				'current_password' => 'required',
				'password' => 'required|min:8',
				'password_confirmation' => 'required|same:password',
			], [
				'current_password.required' => 'Please enter current password',
				'password.required' => 'Please enter new password',
				'password.min' => 'At least :min characters required',
				'password_confirmation.required' => 'Please enter confirm password',
				'password_confirmation.same' => 'Password and Repeat Password does not match',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			$user = User::find(Auth::user()->id);
			if (!Hash::check($request->current_password, $user->password)) {
				return back()->withErrors(['current_password' => 'The specified password does not match the current password']);
			} else {
				$user->password = Hash::make($request->password);
				$user->save();
                return back()->with('success', 'Password has been changed successfully');
			}
		} catch (Exception $e) {
            return back()->with('error', 'Sorry something went worng. Please try again.');
		}
	}
	public function fillProfile(){
		$user_id = Auth::user()->id;
		$admin = User::select('email','name','logo')->where('id',$user_id)->first();

		return view('admin.admin-fill-profile',compact('admin'));
	}
	public function updateProfile(Request $request){
		try {
			$profilePicOrgDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_url'));
			$profilePicThumbDynamicUrl = str_replace('{userSlug}', Auth::user()->slug, config('constant.profile_thumb_url'));


			// echo "<pre>";print_r($request->all());exit;
			$validator = Validator::make($request->all(), [
					'name' => 'required',
				]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			 if ($request->file('profile_photo') != "") {
                $file = $request->file('profile_photo');
                $logo_name = "profile_".time().'.'.$file->getClientOriginalExtension();
				Helper::uploadDynamicFile($profilePicOrgDynamicUrl, $logo_name, $file);
				
                if (isset($request->old_logo) && $request->old_logo != "" && $request->old_logo != '') {
                    Helper::checkFileExists($profilePicOrgDynamicUrl . $request->old_logo, true, true);
                }
            } else {
                $logo_name = $request->old_logo;
			}
			// echo "<pre>";print_r($logo_name);exit;
			
			$user_id = Auth::user()->id;
			$admin = User::updateOrCreate(['id' => $user_id],['logo' => $logo_name, 'name' => $request->name]);
			// dd($admin);
			return redirect()->route('admin.index')->with('success', 'Profile has been updated successfully');
		} catch (Exception $e) {
			return redirect()->route('admin.index')->with('error', 'Sorry something went worng. Please try again.');
		}
	}

	public function removeUser(Request $request){
		try{
			if($request->id){
				DB::beginTransaction();
				$user = User::where('id',$request->id)->first();
				if($user->is_active != 2){
				// if($user->is_active != 0 && $user->is_active != 2){
				
					if($user->type == 3){
						$eventManager = EventManager::where('created_by',$user->id)->select('user_id')->groupBy('user_id')->pluck('user_id')->toArray();
						if(count($eventManager) > 0){
							$eventManager = User::whereIn('id',$eventManager)->delete();
						}
					}
					$user->delete();
					$user->is_active = 2;
					$user->save();
					DB::commit();
					return array('status' => '200', 'msg_success' => 'This Account has been closed successfully');
				}
			}else{
				DB::rollback();
				return array('status' => '0', 'msg_fail' => 'Something went wrong');
			}
		} catch(Exception $e){
			DB::rollback();
			return array('status' => '0', 'msg_fail' => 'Something went wrong');
		}
	}
}
