<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
     public function handle($request, Closure $next)
{
    $guards = ['web', 'driver'];

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            abort(403, 'Cannot access admin panel.');
        }
    }

    return $next($request);
}

}

