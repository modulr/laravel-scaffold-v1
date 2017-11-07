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
$factory->define(App\Models\Events\Event::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->text($maxNbChars = 50),
        'description' => $faker->text,
        'place' => $faker->text(70),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'start_time' => $faker->time($format = 'H:i:s', $max = 'now'),
        'end_time' => $faker->time($format = 'H:i:s', $max = 'now'),
        'attendees' => '[]',
    ];

});
