<?php

use App\Post;
use App\Tag;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // App\User::factory()->count(10)->create();
        factory(App\User::class, 2)->create()->each(function ($user) {
            $user->posts()->createMany(factory(Post::class, 3)->make()->toArray())->each(function ($post) {
                $post->tags()->createMany(factory(Tag::class, 2)->make()->toArray());
            });
        });
    }
}
