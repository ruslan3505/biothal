<?php

namespace App\Http\Middleware;

use Closure;

class CheckForStripe
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
//        dump("CheckForStripe");
        if (session('uuid') == null){
            return route('home');
        }
        return $next($request);
    }
}
