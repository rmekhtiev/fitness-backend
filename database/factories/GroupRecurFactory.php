<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GroupRecur;
use Faker\Generator as Faker;

$factory->define(GroupRecur::class, function (Faker $faker) {
    $startDate = $faker->dateTimeThisYear->setTime(rand(9, 22), $faker->randomElement([0, 30]));
    $endDate = \Carbon\Carbon::instance(clone($startDate))->addHour()->addMinutes($faker->randomElement([0, 30]));

    return [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'recurrence_type' => 'weekly',
        'recurrence_until' => \Carbon\Carbon::instance(clone($endDate))->addYear()->endOfDay(),
    ];
});
