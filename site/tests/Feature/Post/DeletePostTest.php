<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePostTest extends TestCase
{

    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    protected function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    public function test_post_owner_can_delete_post()
    {
        $post = factory(Post::class)->create(["user_id" => $this->user]);

        $response = $this->actingAs($this->user)->delete('/posts/' . $post->post_id);

        $deletedPost = Post::find($post->post_id);

        $this->assertNull($deletedPost);
        $response->assertRedirect("/dashboard");
    }

    public function test_not_owner_cannot_delete_post()
    {

        $post = factory(Post::class)->create();

        $response = $this->actingAs($this->user)->delete('/posts/' . $post->post_id);

        $response->assertRedirect("/");

        $post = Post::find($post->post_id);
        $this->assertNotNull($post);
    }
}
