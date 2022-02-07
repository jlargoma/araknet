<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
          $email = Auth::user()->email;
            if ($email == "jlargo@mksport.es"
                    || $email == "pingodevweb@gmail.com"
                    ) {
                return $next($request);
            } else {
                return response('Unauthorized.', 401);
            }
        }
        return redirect()->guest('login');
    }
}
