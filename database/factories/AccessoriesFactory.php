<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin\Accessories\Accessories;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Accessories::class, function (Faker $faker) {
    return [
        'title' => $faker->text(15),
        'ordering' => $faker->shuffleString(123),
    ];
});
