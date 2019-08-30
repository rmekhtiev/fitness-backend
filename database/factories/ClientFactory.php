<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,

        'phone_number' => $faker->boolean ? $faker->phoneNumber : null,
        'email' => $faker->boolean ? $faker->safeEmail : null,
    ];
});
