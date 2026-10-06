<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  admin, seller, buyer (or any users flag column)
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if(Auth::check()  && Auth::user()->hasDashboardRole($role)){

            if( Auth::user()->status==1){
                return $next($request);
            }else{
              Auth::logout();
            return redirect()->route('login')->with('status','You are suspend User. Pleace contact Authorieze.');
            }

        }else{
            // Logged in with another role: send the user to their own dashboard.
            // dashboardRoute() only names a dashboard whose role check this user passes,
            // so this cannot loop.
            if(Auth::check() && $route =Auth::user()->dashboardRoute()){
                if($role=='seller' && Auth::user()->isBuyer()){
                    session()->flash('info','Activate your seller account to post and manage ads');
                }
                return redirect()->route($route);
            }
            return redirect()->route('index');
        }
    }
}
