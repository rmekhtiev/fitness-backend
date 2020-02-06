<?php

/** @var Factory $factory */

use App\Models\Hall;
use App\Models\Subscription;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'issue_date' => $faker->dateTimeBetween('-30 days', '-10 days'),
        'valid_till' => $faker->dateTimeBetween('+10 days', '+30 days'),
        'cost' => $faker->randomFloat('2')
    ];
});

$factory->state(Subscription::class, 'frozen', function (Faker $faker) {
    return [
        'frozen_start' => $faker->dateTimeBetween('-5 days', 'now'),
        'frozen_till' => $faker->dateTimeBetween('now', '+5 days'),
    ];
});

$factory->state(Subscription::class, 'not_activated', function (Faker $faker) {
    return [
        'issue_date' => $faker->dateTimeBetween('+5 days', '+15 days'),
        'valid_till' => $faker->dateTimeBetween('+25 days', '+45 days'),
    ];
});

$factory->state(Subscription::class, 'expired', function (Faker $faker) {
    return [
        'issue_date' => $faker->dateTimeBetween('-50 days', '-20 days'),
        'valid_till' => $faker->dateTimeBetween('-15 days', '-5 days'),
    ];
});
