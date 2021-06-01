<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(50),
        'body' => $faker->realText(),
        'thumbnail' => $faker->imageUrl(),
        "user_id" => function () {
            return factory(User::class)->create()->user_id;
        },
    ];
});
