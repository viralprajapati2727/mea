<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Auth;
use Helper;
use DB,Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        if ($request->has('reference')) {
            $this->redirectTo1 = $request->input('reference');
        }
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ],[
            'name.required' => "Please provide name",
            'email.required' => "Please provide email",
            'email.email' => "Please enter valid email address",
            'email.unique' => "This email is already exists",
            'password.required' => "Please provide password",
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();
        try{
            $user_data = [
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'name' => $data['name'],
                'is_register_with_platform' => 1,
                'type' => isset($data['user_type'] ) && $data['user_type'] == 2 ? config('constant.USER.TYPE.SIMPLE_USER'):config('constant.USER.TYPE.ENTREPRENEUR'),
                'ip_address' => \Request::ip(),
                'logo' => '',
            ];
            $user = User::create($user_data);

            DB::commit();

            if ($user) {
                return $user;
            } else{
                DB::rollback();
                Log::error('Error occur during registration, please try again.');
            }
        } catch (\Exception $e) {
            DB::rollback();

            $catchError = 'Error code: '.$e->getCode().PHP_EOL;
            $catchError .= 'Error file: '.$e->getFile().PHP_EOL;
            $catchError .= 'Error line: '.$e->getLine().PHP_EOL;
            $catchError .= 'Error message: '.$e->getMessage().PHP_EOL;
            Log::emergency($catchError);
        }

        Session::flash('err_status', 'Error occur during registration, please try again.');
    }

    protected function registered(Request $request, $user){
        $this->guard()->logout();
        return redirect('/')->with('status',trans('auth.account_active'));
    }

    /*
     * Where user redirect after successfully register
     * @return string
     */
    public function redirectTo() {
        $redirectTo = '/';
        if (isset($this->redirectTo1) && $this->redirectTo1 != "") {
            $redirectTo = '/' . $this->redirectTo1;
        }
        $redirectTo = '/login';
        if (Auth::check()) {
            Auth::logout();
        }
        return $redirectTo;
    }
    public function emailExists(Request $request) {
        
        $email = User::where('email', $request->email)->whereNotNull('email_verified_at')->count();
        
		if ($email) {
            return "false";
        } else {
            return "true";
		}
		exit;
    }
    public function checkEmail(Request $request) {       
        $email = User::where('email', $request->email)->count();
        
        if ($email) {
			return "true";
		} else {
			return "false";
		}
		exit;
    }
}
