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
$factory->define(App\Models\Profile\ProfileWork::class, function (Faker\Generator $faker) {

    return [
        'profession_id' => $faker->randomElement([1,2,3]),
        'position_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12]),
        'department_id' => $faker->randomElement([1,2,3,4,5,6]),
        'boss_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
        'starting_from' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];

});
