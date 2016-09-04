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

$factory->define(App\ApiClient::class, function (Faker\Generator $faker) {
	return [
		'client_id' => $faker->company(),
		'client_secret' => str_random(32),
	];
});

$factory->define(App\ApiToken::class, function (Faker\Generator $faker) {
	return [
		'api_token' => str_random(32),
		'refresh_token' => str_random(48),
		'client_id' => factory(App\ApiClient::class)->create()->client_id,
		'user_id' => factory(App\User::class)->create()->id,
		'expires' => Carbon\Carbon::now()->addDay()
	];
});

$factory->define(App\Image::class, function (Faker\Generator $faker) {
	$imgWidth = $faker->numberBetween(1, 640);
	$imgHeight = $faker->numberBetween(1, 480);

	return [
		'id' => rand(3, getrandmax()),
		'name' => $faker->title,
		'url' => $faker->imageUrl($imgWidth, $imgHeight),
		'mime_type' => $faker->mimeType,
		'size' => $imgWidth.'x'.$imgHeight
	];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
	$age = $faker->numberBetween(18, 100);
	$user = factory(App\User::class)->create();
	$image = factory(App\Image::class)->create();

	return [
		'id' => rand(3, getrandmax()),
		'user_id' => $user->id,
		'image_id' => $image->id,
		'firstname' => $faker->firstname,
		'middlename' => $faker->firstname,
		'lastname' => $faker->lastname,
		'phone' => $faker->phoneNumber,
		'email' => $faker->safeEmail,
		'address' => $faker->address,
		'age' => $age,
		'birthday' => Carbon\Carbon::now()->subYears($age)
	];
});

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

