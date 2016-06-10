<?php

namespace App\Http\Controllers\ApiToken;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

use Carbon\Carbon;

use App\ApiToken;
use App\ApiClient;
use App\User;

class ApiTokensController extends Controller
{

    /**
     * Register any middleware.
     *
     * @return Response
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function requestApiToken(Request $request)
    {
        $apiClient = ApiClient::where('client_id', $request->input('client_id'))->first();
        $user = User::where('email', $request->input('email'))->first();

        if ($apiClient->client_secret != $request->input('client_secret')) {
        	return Response::json([
        		'data' => null,
        		'error' => 'Invalid Client credentials.'
        	], 401);
        }

        if ($user == null) {
        	return Response::json([
        		'data' => null,
        		'error' => 'User not found.'
        	], 404);
        }

        $apiTokenObj = [
            'api_token' => str_random(32),
            'client_id' => $apiClient->client_id,
            'user_id' => $user->id,
            'expires' => Carbon::now()->addDay()
        ];

        $apiToken = ApiToken::where(['client_id' => $apiClient->client_id, 'user_id' => $user->id])->first();
        if ($apiToken == null) {
            $apiToken = ApiToken::create($apiTokenObj);
        } else {
            $apiToken->expires = Carbon::now()->addDay();
            $apiToken->save();
        }

        return Response::json([
            'data' => [
                'api_token' => $apiToken,
            ],
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function refreshApiToken(Request $request)
    {
        $apiToken = ApiToken::where('api_token', $request->header('Lanoda-Api_ApiToken'))->first();
        if ($apiToken == null)
        {
            return Response::json([
                'data' => null,
                'error' => 'Api Token not found.'
            ], 401);
        }

        if ($apiToken->client_id != $request->input('client_id')) 
        {
            return Response::json([
                'data' => null,
                'error' => 'Invalid credentials, \'cliend_id\'.',
            ], 401);
        }

        $newApiToken = [
            'api_token' => str_random(32),
            'expires' => Carbon::now()->addDay()
        ];

        $apiToken->update($newApiToken);

        return Response::json([
            'data' => [
                'api_token' => $apiToken,
            ],
        ], 200);
    }
}
