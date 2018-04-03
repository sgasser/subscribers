<?php

use Faker\Generator as Faker;

$factory->define(App\Field::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->randomElement(['name', 'gender', 'zip']),
        'type' => $faker->randomElement(['date', 'number', 'string', 'boolean']),
    ];
});
