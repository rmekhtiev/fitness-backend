<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Schedule;
use Faker\Generator as Faker;

$factory->define(Schedule::class, function (Faker $faker) {
    $startDate = $faker->dateTimeThisYear->setTime(rand(9, 22), $faker->randomElement([0, 30]));
    $endDate = \Carbon\Carbon::instance(clone($startDate))->addHour()->addMinutes($faker->randomElement([0, 30]));

    return [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'recurrence_type' => $faker->randomElement([null, 'weekly', 'daily', 'monthly']),
        'recurrence_until' => \Carbon\Carbon::instance(clone($endDate))->addYear()->endOfDay(),
    ];
});
