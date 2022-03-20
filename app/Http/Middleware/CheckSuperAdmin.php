<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (! (isset($user->is_super) && $user->is_super)) {
            throw new ForbiddenException("cannot access");
        }

        return $next($request);
    }
}
