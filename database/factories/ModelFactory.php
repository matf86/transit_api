<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Transit::class, function (Faker\Generator $faker) {
    return [
        'source_address' => $faker->address,
        'destination_address' => $faker->address,
        'price' => $faker->numberBetween($min = 1, $max = 100000000),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'distance' => $faker->numberBetween($min = 1, $max = 10000000000),
    ];
});
