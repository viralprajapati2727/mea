<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FillProfileAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $user = Auth::user();

            if ($user->type == config('constant.USER.TYPE.ENTREPRENEUR') || $user->type == config('constant.USER.TYPE.SIMPLE_USER')){
                if($user->is_profile_filled != 1) {
                    if($user->type == config('constant.USER.TYPE.ENTREPRENEUR')){
                        if($request->ajax()){
                            return response()->json(['status' => 0, 'message' => trans('page.please_fill_profile'), 'result' => array()]);
                        } else return redirect(route('entrepreneur.fill-profile'))->with('error',trans('page.please_fill_profile'));
                    }
                    else{
                        if($request->ajax()){
                            return response()->json(['status' => 0, 'message' => trans('page.please_fill_profile'), 'result' => array()]);
                        } else return redirect(route('user.fill-profile'))->with('error',trans('page.please_fill_profile'));
                    }
                }
            } else{
                if($request->ajax()){
                    return response()->json(['status' => 0, 'message' => trans('auth.permission'), 'result' => array()]);
                } else return redirect('/')->with('error',trans('auth.permission'));
            }
        }
        return $next($request);
    }
}
