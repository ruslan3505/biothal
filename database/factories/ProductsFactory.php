<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin\Products\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name(15),
        'description' => $faker->text(70),
        'meta_description' => $faker->text(10),
        'meta_keywords' => $faker->text(7),
        'composition' => $faker->text(15),
        'link' => 'my_link',
        'image_id' => 80,
        'price' => 33,

    ];
});

