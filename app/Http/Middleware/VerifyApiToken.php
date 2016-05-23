<?php

namespace App\Http\Middleware;

use Closure;
use App\ApiClient;

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

        if($request->has('api_token'))
        {
            $apiTokenResult = ApiToken::where('api_token', $request->input('api_token'))->first();
            if ($apiTokenResult != null && $apiTokenResult->expires < date('Y-m-d H:i:s')) 
            {
                return true;
            }
        }
    	return false;
    }
}