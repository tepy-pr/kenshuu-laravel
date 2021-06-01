<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauth_user_cannot_see_edit_post_form()
    {
        $post = factory(Post::class)->create();

        $response = $this->get('/posts/' . $post->post_id . "/edit");

        $response->assertStatus(302)->assertRedirect("login");
    }

    public function test_auth_but_not_owner_user_cannot_view_edit_post_form()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $response = $this->actingAs($user)->get('/posts/' . $post->post_id . "/edit");

        $response->assertStatus(403)->assertSee("You do not own this post.");
    }

    public function test_owner_user_can_view_edit_post_form()
    {
        $user = factory(User::class)->create();

        //overwrite $post->user_id
        $post = factory(Post::class)->create(["user_id" => $user]);

        $response = $this->actingAs($user)->get('/posts/' . $post->post_id . "/edit");

        $response->assertCreated();
    }

    public function test_owner_user_can_update_valid_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(["user_id" => $user]);

        $editedData = [
            "title" => $post->title . "-edited",
            "body" => $post->body
        ];

        $response = $this->actingAs($user)->patch('/posts/' . $post->post_id, $editedData);

        $updatedPost = Post::find($post->post_id);

        $this->assertEquals($updatedPost->title, $editedData["title"]);
        $response->assertStatus(302);
        $response->assertRedirect("/");
    }

    public function test_owner_user_cannot_update_invalid_post()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(["user_id" => $user]);

        $editedData = [
            "title" => null,
            "body" => $post->body
        ];

        $response = $this->actingAs($user)->patch('/posts/' . $post->post_id, $editedData);

        $updatedPost = Post::find($post->post_id);

        $this->assertEquals($updatedPost->title, $post->title);
        $response->assertStatus(400);
    }
}
