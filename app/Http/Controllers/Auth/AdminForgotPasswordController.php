<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

use Auth;
use App\User;
use Password;

class AdminForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('admin.auth.email');
    }

    public function sendResetLinkEmail(Request $request) {
        $this->validate($request, ['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (isset($user) && ($user->type == 1 || $user->type == 4 || $user->type == 5)) {
            // dd('jiii');
            // if ($user->type == 4 && $user->is_active != 1) {
                // return back()->with('err_status', trans('auth.Your_account_is_not_deactivated'));
            // }else {
                // dd('hiiii');
                $response = $this->broker()->sendResetLink(
                    $request->only('email')
                );
                
                if ($response === Password::RESET_LINK_SENT) {
                    // dd($response);
                    return back()->with('status', trans($response));
                }
                return back()->withErrors(
                    ['email' => trans($response)]
                );
            // }
        } else{
            return back()->with('err_status', trans('auth.permission'));
        }
    }
}
