<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Session\TokenExpiredException;
use Illuminate\Session\TokenAuthenticationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Response;

use App\ApiClient;
use App\ApiToken;
use App\User;

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

        return Response::Json(['api token' => $request->header('Lanoda-Api_ApiToken')]);
        //throw new TokenMismatchException;
    }

    private function validateApiToken($request) 
    {
    	// Put validation logic here.
        if($request->hasHeader('Lanoda-Api_ApiToken'))
        {
            $apiTokenResult = ApiToken::where('api_token', $request->header('Lanoda-Api_ApiToken'))->first();
            if ($apiTokenResult != null)
            {
                if ($apiTokenResult->expires > Carbon::now())
                {
                    if (Auth::loginUsingId($apiTokenResult->user_id)) {
                        return true;
                    }

                    throw new TokenAuthenticationException;
                }

                throw new TokenExpiredException;
            }
        }

        return false;
    }
}
