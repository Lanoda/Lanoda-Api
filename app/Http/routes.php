<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'users/{user}', 'middleware' => 'api'], function () {
	Route::resource('contacts', 'Contact\ContactsController');
	Route::resource('notes', 'Note\NotesController');
});

Route::get('/', function () {
    return view('welcome', ['apiTokens' => App\ApiToken::all(), 'apiClients' => App\ApiClient::all()]);
});

Route::group(['prefix' => 'api-token'], function() {
	Route::post('request', 'ApiToken\ApiTokensController@requestApiTokenForUser');
	Route::post('refresh', 'ApiToken\ApiTokensController@refreshApiToken');
});

