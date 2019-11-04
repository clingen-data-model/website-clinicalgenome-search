<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

use App\Sessionlog;
use App\Profile;

use Carbon\Carbon;

class NoCache
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
        $response = $next($request);
  
		//return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
		return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		//	->header('Pragma','no-cache')
		//	->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
