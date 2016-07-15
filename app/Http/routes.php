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

Route::get('/', function () {
    return view('welcome', ['apiTokens' => App\ApiToken::all(), 'apiClients' => App\ApiClient::all()]);
});

Route::group(['prefix' => 'api-token'], function() {
	Route::post('request', 'ApiToken\ApiTokensController@requestApiToken');
	Route::put('refresh', 'ApiToken\ApiTokensController@refreshApiToken');
});


Route::group(['prefix' => 'users/{user}', 'middleware' => 'api'], function () {

	// Contacts
	Route::delete('contacts/{contact}', 'Contact\ContactsController@destroy');
	Route::get('contacts', 'Contact\ContactsController@index');
	Route::get('contacts/{contact}', 'Contact\ContactsController@show');
	Route::post('contacts', 'Contact\ContactsController@store');
	Route::put('contacts/{contact}', 'Contact\ContactsController@update');

	// Notes
	Route::resource('notes', 'Note\NotesController');
});

