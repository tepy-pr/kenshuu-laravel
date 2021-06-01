<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Post;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        "url" => $faker->image("images"),
        "post_id" => factory(Post::class)
    ];
});
