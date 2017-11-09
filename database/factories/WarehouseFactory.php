<?php

use Faker\Generator as Faker;
use App\Model\Master\Warehouse;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(Warehouse::class, function (Faker $faker) {
    return [
        'code' => $faker->numberBetween($min = 1000, $max = 9999),
        'name' => $faker->name,
    ];
});
