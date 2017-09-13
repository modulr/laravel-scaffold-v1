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
$factory->define(App\Models\Profile\ProfilePersonal::class, function (Faker\Generator $faker) {

    return [
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'place_of_birth' => $faker->country,
        'gender_id' => $faker->randomElement([1,2]),
        'relationship_id' => $faker->randomElement([1,2,3,4,5,6]),
        'rfc' => $faker->creditCardNumber,
        'curp' => $faker->iban($countryCode = null),
        'nss' => $faker->swiftBicNumber,
    ];

});
