<?php

namespace App\Http\Controllers\ApiClient;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\HttpStatusCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Response;

use Carbon\Carbon;

use App\ApiToken;
use App\ApiClient;
use App\User;

class ApiClientsController extends Controller
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
     *
     * @return Response 
     *
     */
    public function authorizeClient(Request $request) 
    {
        // Make sure ApiClient exists.
        $apiClient = ApiClient::where('client_id', $request->input('client_id'))->first();
        if ($apiClient == null) {
            $apiResult = ApiResult::Error('AuthorizeApp_ClientNotFound', 'ApiClient was not found.');
        	return Response::json($apiResult, HttpStatusCode::NotFound);
        }

        // Verify email and password were sent.
        if ($request->input('email') == null || $request->input('password') == null) {
            $apiResult = ApiResult::Error('AuthorizeApp_MissingRequiredParameters', 'Missing required parameters.');
            return Response::json($apiResult, HttpStatusCode::BadRequest);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        // If user doesn't exist, check if 'register' flag is set. If so, create new user.
        if ($user == null) {
            if ($request->input('register')) {
                $user = User::create(['email' => $email, 'password' => bcrypt($password)]);
            } else {
                $apiResult = ApiResult::Error('AuthorizeApp_InvalidCredentials', 'Invalid Credentials.');
                return Response::json($apiResult, HttpStatusCode::Unauthorized);
            }
        }
        
        $authSuccess = Auth::attempt(['email' => $email, 'password' => $password]);
        
        // If unable to login return unauthorized result.
        if (!$authSuccess) {
            $apiResult = ApiResult::Error('AuthorizeApp_InvalidCredentials', 'Invalid Credentials.');
            return Response::json($apiResult, HttpStatusCode::Unauthorized);
        }

        // After checks pass, create or update the api token.
        $matchThese = ['client_id' => $apiClient->client_id, 'user_id' => Auth::user()->id];
        $apiToken = ApiToken::where($matchThese )->first();
        if ($apiToken == null) {
            $apiToken = ApiToken::create([
                'api_token' => str_random(32),
                'refresh_token' => str_random(48),
                'user_id' => Auth::user()->id,
                'client_id' => $apiClient->client_id,
                'expires' => Carbon::now()->addWeek()
            ]);
        } else {
            $newApiToken = [
                'api_token' => str_random(32),
                'refresh_token' => str_random(48),
                'expires' => Carbon::now()->addWeek()
            ];
            $apiToken->update($newApiToken);
        }

        $apiResult = new ApiResult($apiToken, true);
        return Response::json($apiResult, HttpStatusCode::Ok);
    }

}
