<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FakerRestaurant\Restaurant\ru_RU\Restaurant;
use App\Models\BarItem;
use Faker\Generator as Faker;


$factory->define(BarItem::class, function (Faker $faker) {
    $faker->addProvider(new Restaurant($faker));

    return [
        'title' => $faker->foodName(),
        'amount' => $faker->numberBetween($min=1, $max=100),
        'cost' => $faker->randomNumber(3)
    ];
});
