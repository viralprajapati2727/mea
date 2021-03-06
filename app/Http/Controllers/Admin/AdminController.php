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
		return view('admin.dashboard');
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

	public function paymentSettings(){
		$settings = Setting::where('id',1)->first();
        return view('admin.payment-settings.create',compact('settings'));
	}

	public function storePaymentSetting(Request $request){
		try {
			$validator = Validator::make($request->all(), [
				'platform_commission' => 'required',
				'commission_amount' => 'required',
				'sponser_per_click_price' => 'required',
				'sponser_per_notification_price' => 'required',
				'sponser_per_banner_price' => 'required',
			]);

			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$user = Auth::user();
			$settings = [
				'commision_percentage' => $request->platform_commission,
				'commision_fix_amount' => $request->commission_amount,
				'sponser_per_click_price' => $request->sponser_per_click_price,
				'sponser_per_notification_price' => $request->sponser_per_notification_price,
				'sponser_banner_price' => $request->sponser_per_banner_price,
				'created_by' => $user->id,
				'updated_by' => $user->id,
			];
			$set = Setting::where("id", 1)->update($settings);
			return redirect()->route('admin.payment.settings')->with('success', 'Settings has been updated successfully');
		} catch (Exception $e) {
			return redirect()->route('admin.payment.settings')->with('error', 'Sorry something went worng. Please try again.');
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
