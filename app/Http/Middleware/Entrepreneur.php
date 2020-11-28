<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Entrepreneur {
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
			if ($user->type == config('constant.USER.TYPE.ENTREPRENEUR')){
				if($user->is_active != 1) {
					Auth::logout();
					return redirect('/')->with('error',trans('auth.Your_account_is_not_deactivated'));
				}else{
					return $next($request);
				}
			}
		}
		return redirect('/')->with('error',trans('auth.permission'));
	}
}
