<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UserProfile;
use Helper;
use Auth;
use Session;
use Socialite;
use App\User;
use DB;
use Carbon\Carbon;
use Newsletter;
use Illuminate\Support\Facades\Route;
use Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');

        // if(!empty(request()->all()) && Route::currentRouteName() == 'apple-response'){
        //     $this->appleHandleProvider(request());            
        // }
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'deleted_at' => null,
            'provider_name' => null,
            'is_register_with_platform' => 1,
        ];
    }

    /*
     * After successfully login, we check all schenario of  loggged in user
     * @param Request
     * @param $user
     *
     * @return Redirect
     */
    public function authenticated(Request $request, $user) {
        if($user->type == config('constant.USER.TYPE.ADMIN') || $user->type == config('constant.USER.TYPE.STAFF')) {
            Auth::logout();
            return redirect()->back()->with('error', trans('auth.permission'));
        }else if (($user->type == config('constant.USER.TYPE.SIMPLE_USER') || $user->type == config('constant.USER.TYPE.ENTREPRENEUR')) && ($user->is_active == config('constant.USER.STATUS.Deactive') || ($user->deleted_at != null && $user->deleted_at != '') )) {
            Auth::logout();
            return redirect()->back()->with('error', trans('auth.Your_account_is_not_deactivated'));
        }else if (($user->type == config('constant.USER.TYPE.SIMPLE_USER') || $user->type == config('constant.USER.TYPE.ENTREPRENEUR')) && $user->is_active == config('constant.USER.STATUS.Pending')) {
            Auth::logout();
            return redirect()->back()->with('error', trans('auth.Your_account_is_not_activated_please_activated_first'));
        }
    }

    public function redirectToProvider($social){
        // if($social == 'apple'){
        //     $social = 'sign-in-with-apple';
        //     return Socialite::driver($social)->scopes(["name", "email"])->redirect();
        // }
        return Socialite::driver($social)->redirect();
    }

    public function redirectTo() {
        $redirectTo = '/';
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->type == config('constant.USER.TYPE.ADMIN') || $user->type == config('constant.USER.TYPE.STAFF')) {
                $redirectTo = 'admin';
            } else {
                if($user->is_profile_filled == 1){
                    $redirectTo = 'profile/'.$user->slug;
                } else {
                    if ($user->type == config('constant.USER.TYPE.SIMPLE_USER')) { //simple user
                        $redirectTo = 'user-fill-profile';
                    } else if ($user->type == config('constant.USER.TYPE.ENTREPRENEUR')) { //entrepreneur
                        $redirectTo = 'entrepreneur-fill-profile';
                    }
                }
            }
        }
        return $redirectTo;
    }

    public function checkUniqueEmail(Request $request){
        $exists = User::where([['email',$request->email],['deleted_at',null]])->exists();
        if($exists){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function handleProviderCallback($social, Request $request){
        try{
            
            // echo "<pre>"; print_r($request->all()); exit;
            if (Auth::check()) {
                return redirect('/');
            }
            // if (!$request->has('code') || $request->has('denied')) {
            //     return redirect()->route('index')->with('error','Login failed');
            // }

            if($social == 'apple'){
                $social = 'sign-in-with-apple';
                // $socialData = Socialite::driver($social)->userFromToken();
                // dd($socialData);
            }

            $socialUser = Socialite::driver($social)->user();
            // $socialUser = Socialite::driver($social)->stateless()->user();
            // echo "<pre>"; print_r($socialUser); echo "</pre>";
            // $socialData = Socialite::driver($social)->userFromToken($socialUser->user);
            // dd($socialData); 
            // exit;
            $provider_id = $socialUser->getId();

            // if(!empty($socialUser->getEmail())){
            if($social != 'sign-in-with-apple' ? !empty($socialUser->getEmail()) : 1 == 1){

                // if($social == 'sign-in-with-apple'){
                //     // $user = User::where(['provider_id' => $provider_id,'deleted_at' => null])->first();
                // } else {
                //     $user = User::where(['email' => $socialUser->getEmail(),'deleted_at' => null])->first();
                // }
                $user = User::where(['email' => $socialUser->getEmail(),'deleted_at' => null])->first();
                
                if(is_null($user)){
                    $email = $socialUser->getEmail();
                    // $name = $socialUser->getName();
                    $name = $socialUser->getName() ? $socialUser->getName() : 'nill';

                    return redirect()->route('index',
                        ['token' => $name, 'email' => $email, 'popup_open' => 'social_register', 'social' => $social, 'provider_id' => $provider_id]
                    );
                } else {
                    if($user->type == config('constant.USER.TYPE.ADMIN') || $user->type == config('constant.USER.TYPE.STAFF')) {
                        return redirect()->route('index')->with('error', trans('auth.permission'));
                    }else if (($user->type == config('constant.USER.TYPE.DANCER') || $user->type == config('constant.USER.TYPE.PROFESSIONAL')) && ($user->is_active == config('constant.USER.STATUS.Deactive') || ($user->deleted_at != null && $user->deleted_at != '') )) {
                        return redirect()->route('index')->with('error', trans('auth.Your_account_is_not_deactivated'));
                    }else if (($user->type == config('constant.USER.TYPE.DANCER') || $user->type == config('constant.USER.TYPE.PROFESSIONAL')) && $user->is_active == config('constant.USER.STATUS.Pending')) {
                        DB::beginTransaction();
                        $user->forceFill([
                            'updated_at' => now(),
                            'is_active' => config('constant.USER.STATUS.Active'),
                        ])->save();
                        Auth::login($user);
                        DB::commit();
                        $redirectTo = 'index';
                        if($user->is_profile_filled == 0) {
                            if ($user->type == config('constant.USER.TYPE.DANCER')) { //dancer
                                $redirectTo = 'dancer.fill-profile';
                            } else if ($user->type == config('constant.USER.TYPE.PROFESSIONAL')) { //professional
                                $redirectTo = 'professional.fill-profile';
                            }
                        }
                        return redirect()->route($redirectTo);
                    }else if (($user->type == config('constant.USER.TYPE.DANCER') || $user->type == config('constant.USER.TYPE.PROFESSIONAL')) && ($user->is_active == config('constant.USER.STATUS.Suspended') || $user->is_active == config('constant.USER.STATUS.Suspended By Admin'))){
                        if (($user->suspended_till != null && $user->suspended_till != '') ){
                            $suspended_till =Carbon::parse($user->suspended_till);
                            $today = Carbon::now();
                            if($suspended_till < $today){
                                DB::beginTransaction();
                                $user->forceFill([
                                    'suspended_till' => null,
                                    'is_active' => config('constant.USER.STATUS.Active'),
                                ])->save();
                                DB::commit();
                                Auth::login($user);

                                $redirectTo = 'index';
                                if($user->is_profile_filled == 0) {
                                    if ($user->type == config('constant.USER.TYPE.DANCER')) { //dancer
                                        $redirectTo = 'dancer.fill-profile';
                                    } else if ($user->type == config('constant.USER.TYPE.PROFESSIONAL')) { //professional
                                        $redirectTo = 'professional.fill-profile';
                                    }
                                }
                                return redirect()->route($redirectTo);
                            }
                        }
                        return redirect()->route('index')->with('error', trans('auth.Your_account_is_suspended'));
                    }

                    DB::beginTransaction();
                    $user->forceFill([
                        'updated_at' => now(),
                    ])->save();
                    Auth::login($user);
                    DB::commit();

                    $redirectTo = 'index';
                    if($user->is_profile_filled == 0) {
                        if ($user->type == config('constant.USER.TYPE.DANCER')) { //dancer
                            $redirectTo = 'dancer.fill-profile';
                        } else if ($user->type == config('constant.USER.TYPE.PROFESSIONAL')) { //professional
                            $redirectTo = 'professional.fill-profile';
                        }
                    }
                    return redirect()->route($redirectTo);
                }
            } else {
                return redirect()->route('index')->with('error','Email id is not assigned with this account');
            }

            return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
        } catch(\Exception $e){
            Log::info('Login with social site catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
        }
    }

    public function continueAfterSocialRegister(Request $request){
        try{
            // dd($request->all());
            if (Auth::check()) {
                return redirect('/');
            }
                $redirectTo = '/';

                $user = User::where(['email' => $request->input('email'),'deleted_at' => null])->first();
                if(is_null($user)){
                    DB::beginTransaction();
                    $user = new User();
                    if(!empty($request->input('name')) && $request->input('name') != 'nill'){
                        $user->name = $request->input('name');
                    }
                    $user->email       = $request->input('email');
                    $user->password    = Hash::make(Str::random(8));
                    $user->is_visible_email    = 0;
                    $user->email_verified_at  = now();
                    $user->is_register_with_platform  = 0;
                    $user->provider_name = $request->input('social');
                    $user->provider_id = $request->input('provider_id');
                    $user->is_visible_phone = 0;
                    $user->is_profile_filled = 0;
                    $user->ip_address = \Request::ip();
                    $user->logo = '';
                    $user->is_active = config('constant.USER.STATUS.Active');
                    $user->type = $request->input('is_professional') == 1 ? config('constant.USER.TYPE.PROFESSIONAL') : config('constant.USER.TYPE.DANCER');
                    $user->save();
                    if($user){
                        do{
                            $walletId = str_random(6);
                        }while(UserProfile::where('wallet_unique_id',$walletId)->exists());

                        $reg = UserProfile::updateOrCreate(
                            [ 'user_id' => $user->id ],
                            [ 'wallet_unique_id' =>$walletId]
                        );

                        $allNotification = Helper::notificationSettings($user->id,$user->type);
                    }
                    DB::commit();
                    Auth::login($user);

                    /*
                    * Newsletter subscribe
                    */
                    $subscriber_tag = $request->input('is_professional') == 1?'Professional':'Dancer';
                    Newsletter::subscribeOrUpdate($request->input('email'),[]);

                    if(Newsletter::lastActionSucceeded()){
                        Log::info($request->input('email').' have successfully subscribed to our newsletter as '.$subscriber_tag.' from social register!');
                        Newsletter::addTags([$subscriber_tag], $request->input('email'));
                    }else{
                        $error_message = Newsletter::getLastError();
                        $error_message = trim(preg_replace('/[0-9:]+/', '', $error_message));
                        Log::error('Subscriber Error: '.$error_message);
                    }

                    if ($user->type == config('constant.USER.TYPE.DANCER')) { //dancer
                        $redirectTo = 'dancer.fill-profile';
                    } else if ($user->type == config('constant.USER.TYPE.PROFESSIONAL')) { //professional
                        $redirectTo = 'professional.fill-profile';
                    }
                }

            return redirect()->route($redirectTo);
        } catch(\Exception $e){
            Log::info('Login with social site after select role catch exception:: Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
            DB::rollback();
            return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
        }
    }

    public function socialLoginHandler($social, Request $request){
        try {
           
            $user = Socialite::driver($social)->user();
        
            if ($social == "google") {
                // $finduser = User::where('google_id', $user->id)->first();
                $finduser = User::where('email', $user->getEmail())->first();
                if ($finduser){
                    Auth::login($finduser);
                    return redirect('/profile/'.$finduser->slug);
                } 
                // else {
                //     $newUser = User::create([
                //         'name' => $user->name,
                //         'email' => $user->email,
                //         'google_id'=> $user->id,
                //         "email_verified_at" => Carbon::now()
                //         // 'password' => encrypt('123456dummy')
                //     ]);
                //     Auth::login($newUser);
                //     return redirect('/home');
                //     // return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
                // }
            }
            if ($social == "facebook") {
                // dd($user);
                $finduser = User::where('email', $user->getEmail())->first();

                if($finduser){
                    Auth::login($finduser);
                    return redirect('/profile/'.$finduser->slug);
                } 
                return redirect('/home');
            }
            
            return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
            
        } catch (Exception $e) {
            // dd($e->getMessage());   
            return redirect()->route('index')->with('error',trans('common.something_went_wrong'));
        }
    }
}
