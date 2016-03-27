<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post('/request_token', 'AuthController@requestApiToken');

$app->group(['prefix' => 'api', 'middleware' => 'auth:api'], function () use ($app) {
	$app->get('user/{user_id}/profile', function ($user_id) {
		return $user_id;		
	});
});

