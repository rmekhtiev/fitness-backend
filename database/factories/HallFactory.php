<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Hall;
use Faker\Generator as Faker;

$factory->define(Hall::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'address' => $faker->streetAddress,
    ];
});
