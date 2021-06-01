<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePostTest extends TestCase
{

    use RefreshDatabase;

    public function test_post_owner_can_delete_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(["user_id" => $user]);

        $response = $this->actingAs($user)->delete('/posts/' . $post->post_id);

        $deletedPost = Post::find($post->post_id);

        $this->assertNull($deletedPost);
        $response->assertRedirect("/dashboard");
    }

    public function test_not_owner_cannot_delete_post()
    {

        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $response = $this->actingAs($user)->delete('/posts/' . $post->post_id);

        $response->assertRedirect("/");

        $post = Post::find($post->post_id);
        $this->assertNotNull($post);
    }
}
