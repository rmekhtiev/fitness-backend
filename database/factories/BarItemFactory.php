<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\BarItem;
use Faker\Generator as Faker;

$factory->define(BarItem::class, function (Faker $faker) {
    return [
        'title' => $faker->company,
        'amount' => $faker->numberBetween($min=1, $max=100),
    ];
});
