<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Carbon\Carbon;
use App\ApiClient;
use App\ApiToken;

class VerifyApiToken
{

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
        if($request->hasHeader('Lanoda-Api_ApiToken'))
        {
            $apiTokenResult = ApiToken::where('api_token', $request->header('Lanoda-Api_ApiToken'))->first();
            if ($apiTokenResult != null && $apiTokenResult->expires > Carbon::now())
            {
                return true;
            }
        }
    	return false;
    }
}