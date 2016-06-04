<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
    	'id' => rand(3, getrandmax()),
        'firstname' => $faker->firstName(),
        'lastname' => $faker->lastName(),
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\ApiClient::class, function (Faker\Generator $faker) {
	return [
		'client_id' => $faker->company(),
		'client_secret' => str_random(32),
	];
});

$factory->define(App\ApiToken::class, function (Faker\Generator $faker) {
	return [
		'api_token' => str_random(32),
		'client_id' => factory(App\ApiClient::class)->create()->client_id,
		'user_id' => factory(App\User::class)->create()->id,
		'expires' => Carbon\Carbon::now()->addDay()
	];
});

