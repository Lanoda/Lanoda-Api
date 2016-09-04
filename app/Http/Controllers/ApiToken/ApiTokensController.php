<?php

namespace App\Http\Controllers\ApiToken;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\HttpStatusCode;
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
     * [Deprecated] Display a listing of the resource.
     *
     * @return Response
     */
    public function requestApiToken(Request $request)
    {
        // Get ApiClient and Verify secret.
        $apiClient = ApiClient::where('client_id', $request->input('client_id'))->first();
        if ($apiClient == null || $apiClient->client_secret != $request->input('client_secret')) {
            $apiResult = ApiResult::Error('ApiTokenRequest_InvalidClientCredentials', 'Invalid Client credentials.');
        	return Response::json($apiResult, HttpStatusCode::BadRequest);
        }

        // Get User by email.
        $user = User::where('email', $request->input('email'))->first();
        if ($user == null) {
            $apiResult = ApiResult::Error('ApiTokenRequest_UserNotFound', 'User not found.');
        	return Response::json($apiResult, HttpStatusCode::NotFound);
        }

        // Setup ApiToken object.
        $apiTokenObj = [
            'api_token' => str_random(32),
            'client_id' => $apiClient->client_id,
            'user_id' => $user->id,
            'expires' => Carbon::now()->addDay()
        ];

        // Update existing ApiToken, or create new.
        $apiToken = ApiToken::where(['client_id' => $apiClient->client_id, 'user_id' => $user->id])->first();
        if ($apiToken == null) {
            $apiToken = ApiToken::create($apiTokenObj);
        } else {
            $apiToken->expires = Carbon::now()->addDay();
            $apiToken->save();
        }

        // Return result
        $apiResult = new ApiResult($apiToken, true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function refreshApiToken(Request $request)
    {
        // Get existing ApiToken.
        $apiToken = ApiToken::where('refresh_token', $request->input('refresh_token'))->first();
        if ($apiToken == null)
        {
            $apiResult = ApiResult::Error('ApiTokenRefresh_ApiTokenNotFound', 'Api Token not found.');
            return Response::json($apiResult, HttpStatusCode::NotFound);
        }

        // Check if matching client_id.
        if ($apiToken->client_id != $request->input('client_id')) 
        {
            $apiResult = ApiResult::Error('ApiTokenRefresh_InvalidCredentials', 'Invalid credentials, \'client_id\'.');
            return Response::json($apiResult, HttpStatusCode::BadRequest);
        }

        // Update ApiToken.
        $newApiToken = [
            'api_token' => str_random(32),
            'refresh_token' => str_random(48),
            'expires' => Carbon::now()->addWeek()
        ];
        $apiToken->update($newApiToken);

        // Return result;
        $apiResult = new ApiResult($apiToken, true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }
}
