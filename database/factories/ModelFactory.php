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
        'name' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\County::class, function (Faker\Generator $faker) {
    $faker->locale = 'zh_TW';

    return [
        'name' => $faker->unique()->city,
    ];
});

$factory->define(App\Township::class, function (Faker\Generator $faker) {
    $faker->locale = 'zh_TW';

    return [
        'name' => $faker->unique()->city,
    ];
});

$factory->define(App\Site::class, function (Faker\Generator $faker) {
    $faker = Faker\Factory::create('zh_TW');
    $enFaker = Faker\Factory::create('en_US');

    return [
        'name' => $faker->unique()->word,
        'humanized_name' => $faker->unique()->word,
        'humanized_eng_name' => $enFaker->unique()->word,
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

$factory->state(App\Site::class, 'lass', function (Faker\Generator $faker) {
    return [
        'source_type' => 'lass',
    ];
});

$factory->define(App\EpaDataset::class, function (Faker\Generator $faker) {
    $fakedPublishedDatetime = $faker->dateTime();

    return [
        'aqi' => $faker->numberBetween(1, 20),
        'so2' => $faker->randomFloat(1, 0, 999),
        'co' => $faker->randomDigit(2),
        'co_8hr' => $faker->randomDigit(2),
        'o3' => $faker->randomDigit,
        'o3_8hr' => $faker->randomDigit,
        'pm10' => $faker->numberBetween(1, 120),
        'pm10_avg' => $faker->numberBetween(1, 120),
        'pm25' => $faker->numberBetween(1, 300),
        'pm25_avg' => $faker->numberBetween(1, 300),
        'no2' => $faker->numberBetween(1, 60),
        'wind_speed' => $faker->randomFloat(1, 0, 9999),
        'wind_direction' => $faker->randomDigit,
        'nox' => $faker->randomFloat(1, 0, 9999),
        'no' => $faker->randomFloat(1, 0, 9999),
        'pollutant' => $faker->word,
        'status' => $faker->word,
        'site_id' => function () {
            return factory(App\Site::class)->create()->id;
        },
        'published_datetime' => $fakedPublishedDatetime->format('Y-m-d H:i:s'),
    ];
});

$factory->define(App\LassDataset::class, function (Faker\Generator $faker) {
    $fakedPublishedDatetime = $faker->dateTime();

    return [
        'pm25' => $faker->randomFloat(1, 0, 9999),
        'pm10' => $faker->randomFloat(1, 0, 9999),
        'temperature' => $faker->randomFloat(2, 0, 999),
        'humidity' => $faker->randomFloat(2, 0, 999),
        'site_id' => function () {
            return factory(App\Site::class)->create()->id;
        },
        'published_datetime' => $fakedPublishedDatetime->format('Y-m-d H:i:s'),
    ];
});

$factory->define(App\AirboxDataset::class, function (Faker\Generator $faker) {
    $fakedPublishedDatetime = $faker->dateTime();

    return [
        'pm25' => $faker->randomFloat(1, 0, 9999),
        'pm10' => $faker->randomFloat(1, 0, 9999),
        'temperature' => $faker->randomFloat(2, 0, 999),
        'humidity' => $faker->randomFloat(2, 0, 999),
        'site_id' => function () {
            return factory(App\Site::class)->create()->id;
        },
        'published_datetime' => $fakedPublishedDatetime->format('Y-m-d H:i:s'),
    ];
});

$factory->define(\App\AggregationLog::class, function (Faker\Generator $faker) {
    return [
        'aggregation_type' => 'daily',
        'source_type' => 'lass',
        'start_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'end_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'message' => $faker->word,
        'level' => \Monolog\Logger::INFO,
    ];
});

$factory->state(\App\AggregationLog::class, 'daily', function (Faker\Generator $faker) {
    return [
        'aggregation_type' => 'daily',
        'source_type' => 'lass',
        'start_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'end_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'message' => $faker->word,
        'level' => \Monolog\Logger::INFO,
    ];
});

$factory->state(\App\AggregationLog::class, 'hourly', function (Faker\Generator $faker) {
    return [
        'aggregation_type' => 'hourly',
        'source_type' => 'lass',
        'start_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'end_datetime' => $faker->unique()->dateTime()->format('Y-m-d H:i:s'),
        'message' => $faker->word,
        'level' => \Monolog\Logger::INFO,
    ];
});
