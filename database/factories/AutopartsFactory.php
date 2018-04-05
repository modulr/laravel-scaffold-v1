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
$factory->define(App\Models\Autoparts\Autopart::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text($maxNbChars = 50),
        'description' => $faker->text,
        'purchase_price' => $faker->numberBetween($min = 1000, $max = 9000),
        'sale_price' => $faker->numberBetween($min = 10000, $max = 20000),
        'make_id' => $faker->randomElement([1,2]),
        'model_id' => $faker->randomElement([1,2,3,4,5]),
        'origin_id' => $faker->randomElement([1,2,3]),
        'status_id' => $faker->randomElement([1,2,3,4])
    ];

});
