<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Locker;
use Faker\Generator as Faker;

$factory->define(Locker::class, function (Faker $faker) {
    return [
        'number' => $faker->unique()->numberBetween(0, 500),
    ];
});
