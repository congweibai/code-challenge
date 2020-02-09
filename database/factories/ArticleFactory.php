<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'boby' => $faker->text(200),
        'picture_url' => $faker->text(100),
        'picture_id' => $faker -> text(8)
    ];
});
