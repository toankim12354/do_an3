<?php

namespace App\Http\Middleware;

use App\Exceptions\UserNotActiveException;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckUserActive
{
    /**
     * if user not active -> logout -> show error page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::user()->status) {
            Auth::logout();

            throw new UserNotActiveException("Your Account Not Active");
        }

        return $next($request);
    }
}
