<?php

namespace App\Http\Controllers\ApiClient;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\ApiResult;
use App\Http\Controllers\Helpers\ApiErrorResult;
use App\Http\Controllers\Helpers\HttpStatusCode;
use App\Http\Controllers\Helpers\ErrorList;

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
            $apiResult = new ApiErrorResult('AuthorizeApp_ClientNotFound');
        	return $apiResult->GetJsonResponse('NotFound');
        }

        // Verify email and password were sent.
        if ($request->input('email') == null || $request->input('password') == null) {
            $apiResult = new ApiErrorResult('AuthorizeApp_MissingRequiredParameters');
            return $apiResult->GetJsonResponse('BadRequest');
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        // If user doesn't exist, check if 'register' flag is set. If so, create new user.
        if ($user == null) {
            if ($request->input('register')) {
                $user = User::create(['email' => $email, 'password' => bcrypt($password)]);
            } else {
                $apiResult = new ApiErrorResult('AuthorizeApp_InvalidCredentials');
                return $apiResult->GetJsonResponse('Unauthorized');
            }
        }
        
        $authSuccess = Auth::attempt(['email' => $email, 'password' => $password]);
        
        // If unable to login return unauthorized result.
        if (!$authSuccess) {
            $apiResult = new ApiErrorResult('AuthorizeApp_InvalidCredentials');
            return $apiResult->GetJsonResponse('Unauthorized');
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

            $apiResult = new ApiResult($apiToken, true);
            return $apiResult->GetJsonResponse('Ok');
        }
        
        $newApiToken = [
            'api_token' => str_random(32),
            'refresh_token' => str_random(48),
            'expires' => Carbon::now()->addWeek()
        ];
        $apiToken->update($newApiToken);

        $apiResult = new ApiResult($apiToken, true);
        return $apiResult->GetJsonResponse('Ok');
    }

}
