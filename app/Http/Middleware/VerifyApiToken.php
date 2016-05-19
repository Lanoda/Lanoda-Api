<?php

namespace App\Http\Middleware;

use Closure;

class VerifyApiToken {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($this->validateApiToken($request)) {
            return $next($request);
        }

        throw new TokenMismatchException;
    }

    private function validateApiToken($request) 
    {
    	// Put validation logic here.

        if($request->input('api_token') != null) {
            
        }
    	return true;
    }
}