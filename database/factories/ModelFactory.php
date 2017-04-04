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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\County::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'name' => $faker->city,
    ];
});

$factory->define(App\Township::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('zh_TW');

    return [
        'name' => $faker->city,
    ];
});

$factory->define(App\Site::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('zh_TW');
    $enFaker = Faker\Factory::create('en_US');

    return [
        'name' => $faker->word,
        'eng_name' => $enFaker->word,
        'area_name' => $faker->city,
        'coordinates' => [
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
        ],
        'type' => $faker->word,
        'source_type' => 'epa',
        'county_id' => function () {
            return factory(App\County::class)->create()->id;
        },
        'township_id' => function () {
            return factory(App\Township::class)->create()->id;
        },
    ];
});

$factory->state(App\Site::class, 'epa', function (Faker\Generator $faker) {
    return [
        'source_type' => 'epa',
    ];
});