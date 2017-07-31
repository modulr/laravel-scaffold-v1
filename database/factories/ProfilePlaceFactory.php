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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Profile\ProfilePlace::class, function (Faker\Generator $faker) {

    return [
        'place' => $faker->address,
        'currently' => $faker->randomElement([0,1])
    ];

});
