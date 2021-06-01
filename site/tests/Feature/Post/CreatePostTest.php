<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauth_user_cannot_see_create_post_form()
    {
        $response = $this->get('/posts/create');

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function test_auth_user_can_see_create_post_form()
    {
        $user = factory(User::class)->create();
        $route = "/posts/create";

        $response = $this->actingAs($user)->get($route);

        $response->assertOk();
        $response->assertViewIs("posts.create");
    }

    public function test_auth_user_can_save_post()
    {
        Storage::fake("images");

        $user = factory(User::class)->create();
        $route = "/posts";

        $image1 = UploadedFile::fake()->image("test1.png");
        $image2 = UploadedFile::fake()->image("test2.png");
        $images = [$image1, $image2];

        $post = factory(Post::class)->make(["thumbnail" => "test1.png", "user_id" => $user->user_id]);

        $data = [
            "title" => $post->title,
            "body" => $post->body,
            "tags" => "a, b, c",
            "postImages" => $images,
        ];

        $response = $this->actingAs($user)->post($route, $data);

        $response->assertCreated();
    }

    public function test_unauth_user_cannot_save_post()
    {
        Storage::fake("images");

        $user = factory(User::class)->create();
        $route = "/posts";

        $image1 = UploadedFile::fake()->image("test1.png");
        $image2 = UploadedFile::fake()->image("test2.png");
        $images = [$image1, $image2];

        $post = factory(Post::class)->make(["thumbnail" => "test1.png", "user_id" => $user->user_id]);

        $data = [
            "title" => $post->title,
            "body" => $post->body,
            "tags" => "a, b, c",
            "postImages" => $images,
        ];

        $response = $this->post($route, $data);

        $response->assertStatus(302);
        $response->assertRedirect("login");
    }

    public function test_auth_user_cannot_save_invalid_post()
    {
        Storage::fake("images");

        $user = factory(User::class)->create();
        $route = "/posts";

        $image1 = UploadedFile::fake()->image("test1.png");
        $image2 = UploadedFile::fake()->image("test2.png");
        $images = [$image1, $image2];

        $post = factory(Post::class)->make(["thumbnail" => "test1.png", "user_id" => $user->user_id]);

        $data = [
            "title" => "",
            "body" => $post->body,
            "tags" => "a, b, c",
            "postImages" => $images,
        ];

        $response = $this->actingAs($user)->post($route, $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["title"]);
    }
}
