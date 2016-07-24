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


// User-specific methods
Route::group(['middleware' => 'api'], function () {

	// Contacts
	Route::delete('contacts/{contact}', 'Contact\ContactsController@destroy');
	Route::get('contacts', 'Contact\ContactsController@showlist');
	Route::get('contacts/{contact}', 'Contact\ContactsController@show');
	Route::post('contacts', 'Contact\ContactsController@store');
	Route::put('contacts/{contact}', 'Contact\ContactsController@update');

	// Notes
	Route::delete('notes/{note}', 'Note\NotesController@destroy');
	Route::get('notes', 'Note\NotesController@showlist');
	Route::get('contacts/{contact}/notes', 'Note\NotesController@showlistForContact');
	Route::get('notes/{note}', 'Note\NotesController@show');
	Route::post('notes', 'Note\NotesController@store');
	Route::put('notes/{note}', 'Note\NotesController@update');
});

