<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
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

        if (Auth::check()) {
            return redirect('/');
        }
        return redirect()->route('index',['token' => $token, 'email' => $request->email, 'popup_open' => 'reset']);
        // return view('welcome')->with(
        //     ['token' => $token, 'email' => $request->email, 'popup_open' => 'reset']
        // );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {

        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );
        $userData = User::select('id','type','email','name')->where('email',$request->email)->first();
        if($userData->type == 5){
            SendMailController::dynamicEmail([
                'email_id' => 24,
                'user_id' => 1,
                'type' => $userData->type,
                'user_name' => $userData->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        }
// echo"<pre>";print_r($request->all());exit;
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetCustomFailedResponse($request, $response);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetCustomFailedResponse(Request $request, $response)
    {
        return redirect()->route('index')->with('error',trans($response));
    }
}
