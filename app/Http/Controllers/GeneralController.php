<?php
namespace App\Http\Controllers;

use App\Http\Controllers\SendMailController;
use App\User;
use Auth;
use DB;
use Hash;
use Helper;
use Illuminate\Http\Request;
use Validator;
use App;
use Route;
use PHPUnit\Framework\Exception;

class GeneralController extends Controller {
	public function index() {
        return view('welcome');
	}
	public function changePassword() {
		return view('pages.change_password');
    }
    public function updatePassword(Request $request) {
		try {
			$loggedInUser = Auth::user();
			$validator = Validator::make($request->all(), [
				'current_password' => 'required',
				'new_password' => 'required|min:6',
				'confirm_password' => 'required|same:new_password'
			], [
				'current_password.required' => trans('auth.Please_enter_old_password'),
				'new_password.required' => trans('auth.Please_enter_new_password'),
				'new_password.min' => trans('auth.AT_LEAST_CHARACTERS_REQUIRED'),
				'confirm_password.required' => trans('auth.Please_enter_confirm_password'),
				'confirm_password.same' => trans('auth.Please_enter_the_same_password_as_above'),
			]);
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}

			DB::beginTransaction();
				$loggedInUser->password = Hash::make($request->new_password);
				$loggedInUser->save();
			DB::commit();
			return redirect()->back()->with('success',trans('auth.Password_changed_successfully'));

		} catch (Exception $e) {
			Log::emergency('change password exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
			return back()->with('error',trans('app.something_went_wrong'));
		}
	}
}
