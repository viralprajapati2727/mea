<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    /**
     * Override method
     *
     * @return (array) redirect
     */
    public function verify(Request $request){
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(stripos($ua,'android') !== false && stripos($ua,'mobile') !== false) {
            $post = $request->all();

            if(!empty($post) && $request->m !== true){
                $full_url = url()->full();
                // $full_url = str_replace('http','https',$full_url);
                $url = stripslashes($full_url);
                $afterQ = explode('?',$url);
                $beforeQ = explode('/',$afterQ[0]);
                unset($beforeQ[0]);
                unset($beforeQ[1]);
                array_pop($beforeQ);
                $before_url = implode('/', $beforeQ);

                $redirect_url = $full_url."?m=true";
                Log::info('Verify from mobile phone intent'.'intent://'.$before_url.'?url='.$full_url.'#Intent;scheme=https;package=com.dancero.dance;S.browser_fallback_url='.$redirect_url.';end');
                return redirect('intent://'.$before_url.'?url='.$full_url.'#Intent;scheme=https;package=com.dancero.dance;S.browser_fallback_url='.$redirect_url.';end')->with(['verified'=> true, 'status' => trans('auth.account_verified') ]);
            }
        }

        $user = User::findOrFail($request->route('id'));

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            if(Auth::check())
                return redirect($this->redirectPath())->with('status',trans('auth.account_already_verified_after_login'));
            else
                return redirect($this->redirectPath())->with('status',trans('auth.account_already_verified'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        SendMailController::dynamicEmail([
            'email_id' => 2,
            'user_id' => $user->id,
        ]);

        return redirect('/')->with(['verified'=> true, 'status' => trans('auth.account_verified') ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user = User::where('email', $request->input('email')) ->where('deleted_at', null) ->get() ->first();

        if(isset($user->id)){
            if( $user->email_verified_at != null || $user->email_verified_at != '' || $user->is_register_with_platform != 1 || $user->is_active != config('constant.USER.STATUS.Pending') ){
                return redirect('/')->with('status',trans('auth.account_already_verified'));
            }
            $user->sendEmailVerificationNotification();
            return redirect('/')->with('status',trans('auth.account_active'));
        } else{
            return redirect('/')->with('error',trans('auth.email_not_identified'));
        }
    }
}
