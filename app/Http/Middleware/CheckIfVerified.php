<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;

use Closure;
use Auth;

class CheckIfVerified
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
        $routes = ['auth.verify'];
        if (Auth::check()) {
            $user = Auth::user();
            if($user->isVerified === 1) {
                if (in_array(Route::currentRouteName(), $routes)) {
                    return redirect()->route('home');
                } else {
                    return $next($request);
                }
                return $next($request);
            } else {
                return redirect()->route('auth.verify');
            }
        } else {
            return redirect()->route('auth');
        }
        return $next($request);
    }
}
