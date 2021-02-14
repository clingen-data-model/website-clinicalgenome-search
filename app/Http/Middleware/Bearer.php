<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;
use Auth;

class Bearer extends Middleware
{
    /**
     * Attach the token cookie to all requests
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->cookie('laravel_token')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->cookie('laravel_token'));
            //$request->middleware('auth:api');
        }

        //$this->authenticate($request, $guards);

        return $next($request);
    }
}
