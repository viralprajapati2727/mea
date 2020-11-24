<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use View;
use Helper;

class Admin {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if(Auth::check()){
			$user = Auth::user();
            $privileges = array();
			if ($user->type != 1) {
				if ($user->type == 5 && $user->is_active != 1) {
					Auth::logout();
					return redirect('/')->with('error','Your account is deactivated, Please contact admin to activate your account');
				}else if ($user->type != 5) {
					return redirect('/')->with('error','You do not have sufficient permissions to access this page.');
				}
            }
            if ($user->type == 5){
                if(isset($user->staffPrivilege)){
                    $privileges = collect($user->staffPrivilege)->pluck('access_id')->toArray();
                }
                if($request->isMethod('get')){
					if(!Helper::staffAccessMenu($privileges)){
                        return redirect()->route('admin.index')->with('error','You do not have sufficient permissions to access this page.');
                    }
                }
            }
            View::share(compact('privileges'));
			return $next($request);
		}else{
			return redirect()->route('admin');
		}
	}
}
