<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {    
		if(!Auth::check())
        {
            return redirect('login');
        }
        
        $roles = ['admin','user','admin'];
        
        $role = $roles[Auth()->user()->role];
        
		//Sharing variable with view
		view()->share('role', $role);
		
		//Setting Attribute to use in Controller
		$request->role = $role;
        
        return $next($request);
    }
}
