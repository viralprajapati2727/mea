<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\User;
use Illuminate\Auth\Passwords\CanResetPassword;

class ForgotPasswordController extends Controller
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

    use SendsPasswordResetEmails, CanResetPassword;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request){
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where([
            'email'=> $request->email,
            'deleted_at' => null,
            'is_register_with_platform' => 1,
        ])->first();

        if(isset($user->id)){
            if($user->type == config('constant.USER.TYPE.ADMIN') || $user->type == config('constant.USER.TYPE.STAFF')) {
                return redirect()->back()->with('error', trans('auth.permission'));
            }else if (($user->type == config('constant.USER.TYPE.SIMPLE_USER') || $user->type == config('constant.USER.TYPE.ENTREPRENEUR')) && ($user->is_active == config('constant.USER.STATUS.Deactive') || ($user->deleted_at != null && $user->deleted_at != '') )) {
                return redirect()->back()->with('error', trans('auth.Your_account_is_not_deactivated'));
            }else if (($user->type == config('constant.USER.TYPE.SIMPLE_USER') || $user->type == config('constant.USER.TYPE.ENTREPRENEUR')) && $user->is_active == config('constant.USER.STATUS.Pending')) {
                return redirect()->back()->with('error', trans('auth.Your_account_is_not_activated_please_activated_first'));
            }else if (($user->type == config('constant.USER.TYPE.SIMPLE_USER') || $user->type == config('constant.USER.TYPE.ENTREPRENEUR')) && ($user->is_active == config('constant.USER.STATUS.Active'))){
                $response = $this->broker()->sendResetLink(
                    ["email" => $request->only('email')]
                );

                if ($response === Password::RESET_LINK_SENT) {
                    return back()->with('success',trans($response));
                }
                return back()->with(
                    ['error' => trans($response)]
                );
            }
        } else{
            return redirect('/')->with('error',trans('auth.email_not_identified'));
        }
    }

}
