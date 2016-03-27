<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    // Controller Functions

    public function requestApiToken(Request $request) 
    {
        if ($request->input('email') && $request->input('password')) 
        {
            $user = User::where('email', $request->input('email'))->first();
            if ($user != null) 
            {
                if (Hash::check($request->input('password'), $user->password)) 
                {
                    $apiToken = $this->GenerateApiToken($user);
                    return response()->json(['api_token' => $apiToken]);
                }
                return 'Password was incorrect.';
            }
            return 'User Not found';
        }
    }


    protected function GenerateApiToken(User $user) 
    {
        $apiToken = null;
        do {
            $apiToken = str_random(60);
            $existingUserWithApiToken = User::where('api_token', $apiToken)->first();
        } while($existingUserWithApiToken != null);

        $user->api_token = $apiToken;
        $user->api_token_expires = Carbon::now()->addMinute()->toDateTimeString();
        $user->save();

        return $user->api_token;
    }
}

