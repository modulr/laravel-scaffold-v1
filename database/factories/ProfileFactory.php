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

// Personal
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

// Contact
$factory->define(App\Models\Profile\ProfileContact::class, function (Faker\Generator $faker) {

    return [
        'contact' => $faker->email(),
        'type_id' => 1,
    ];

});

// Education
$factory->define(App\Models\Profile\ProfileEducation::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->catchPhrase,
        'school_name' => $faker->company,
        'start_year' => $faker->year($max = 'now'),
        'end_year' => $faker->year($max = 'now'),
    ];

});

// Family
$factory->define(App\Models\Profile\ProfileFamily::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'gender_id' => $faker->randomElement([1,2]),
        'relation_id' => $faker->randomElement([1,2,3,4,5,6,7,8]),
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];

});

// Place
$factory->define(App\Models\Profile\ProfilePlace::class, function (Faker\Generator $faker) {

    return [
        'place' => $faker->address,
        'currently' => $faker->randomElement([0,1])
    ];

});

// Work
$factory->define(App\Models\Profile\ProfileWork::class, function (Faker\Generator $faker) {

    return [
        'profession_id' => $faker->randomElement([1,2,3]),
        'position_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12]),
        'department_id' => $faker->randomElement([1,2,3,4,5,6]),
        'boss_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10]),
        'starting_from' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];

});
