<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\Gender;
use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    $gender = $faker->boolean ? Gender::MALE : Gender::FEMALE;

    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'gender' => $gender,

        'phone_number' => $faker->phoneNumber,
        'email' => $faker->boolean ? $faker->safeEmail : null,
    ];
});
