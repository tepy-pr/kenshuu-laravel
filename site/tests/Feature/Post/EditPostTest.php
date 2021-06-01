<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $post;
    protected $postWithUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->post = factory(Post::class)->create();
        $this->postWithUser = factory(Post::class)->create(["user_id" => $this->user]);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->post->delete();
        $this->postWithUser->delete();

        parent::tearDown();
    }

    public function test_unauth_user_cannot_see_edit_post_form()
    {
        $response = $this->get('/posts/' . $this->post->post_id . "/edit");

        $response->assertStatus(302)->assertRedirect("login");
    }

    public function test_auth_but_not_owner_user_cannot_view_edit_post_form()
    {
        $response = $this->actingAs($this->user)->get('/posts/' . $this->post->post_id . "/edit");

        $response->assertStatus(403)->assertSee("You do not own this post.");
    }

    public function test_owner_user_can_view_edit_post_form()
    {
        $response = $this->actingAs($this->user)->get('/posts/' . $this->postWithUser->post_id . "/edit");

        $response->assertCreated();
    }

    public function test_owner_user_can_update_valid_post()
    {
        $editedData = [
            "title" => $this->postWithUser->title . "-edited",
            "body" => $this->postWithUser->body
        ];

        $response = $this->actingAs($this->user)->patch('/posts/' . $this->postWithUser->post_id, $editedData);

        $updatedPost = Post::find($this->postWithUser->post_id);

        $this->assertEquals($updatedPost->title, $editedData["title"]);
        $response->assertStatus(302);
        $response->assertRedirect("/");
    }

    public function test_owner_user_cannot_update_invalid_post()
    {
        $editedData = [
            "title" => null,
            "body" => $this->postWithUser->body
        ];

        $response = $this->actingAs($this->user)->patch('/posts/' . $this->postWithUser->post_id, $editedData);

        $updatedPost = Post::find($this->postWithUser->post_id);

        $this->assertEquals($updatedPost->title, $this->postWithUser->title);
        $response->assertStatus(400);
    }
}
