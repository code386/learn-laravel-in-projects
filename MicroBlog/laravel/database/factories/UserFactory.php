<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    $data_time = $faker->date . ' ' . $faker->time;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'activated' => true,
        'password' => '$2y$10$ye50y5AlpEEXiLrDURBodeUwNqvI7zYXT8hSV4UZn/pmCZQJPWV2e', // secret
        'remember_token' => Str::random(10),
        'created_at' => $data_time,
        'updated_at' => $data_time
    ];
});