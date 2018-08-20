<?php

namespace DMZ\Http\Middleware;

use Closure;

class CORS
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
        header('Access-control-Allow-Origin : *');
        header('Access-control-Allow-headers : Content-type, X-Auth-Token, Authorization, Origin');
        return $next($request);
    }
}
