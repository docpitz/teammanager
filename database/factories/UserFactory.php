<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'firstname' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->safeEmail,
        'email_optional' => $faker->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('secret'), // secret
        'remember_token' => Str::random(10),
        'created_at' => now(),
        'updated_at' => now()
    ];
});
