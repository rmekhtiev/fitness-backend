<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\Gender;
use App\Enums\QuestionnaireStatus;
use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    $gender = $faker->boolean ? Gender::MALE : Gender::FEMALE;

    return [
        'first_name' => $faker->firstName($gender),
        'last_name' => $faker->lastName,
        'birth_date' => $faker->date(),
        'gender' => $gender,

        'phone_number' => $faker->unique()->phoneNumber,
        'whats_app_number' => $faker->unique()->phoneNumber,
        'instagram' => $faker->unique()->userName,
        'questionnaire_status' => QuestionnaireStatus::UNFILLED,
    ];
});
