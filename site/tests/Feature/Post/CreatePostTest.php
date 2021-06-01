<?php

namespace Tests\Feature\Post;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $post;
    protected $validData;
    protected $invalidData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->post = factory(Post::class)->make(["thumbnail" => "test1.png", "user_id" => $this->user->user_id]);
        $this->validData = $this->generatePostData();
        $this->invalidData = $this->generatePostData(false);
    }

    protected function tearDown(): void
    {
        $this->user->delete();
        $this->post->delete();

        parent::tearDown();
    }

    public function test_unauth_user_cannot_see_create_post_form()
    {
        $response = $this->get('/posts/create');

        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }

    public function test_auth_user_can_see_create_post_form()
    {
        $route = "/posts/create";

        $response = $this->actingAs($this->user)->get($route);

        $response->assertOk();
        $response->assertViewIs("posts.create");
    }

    public function test_auth_user_can_save_post()
    {
        Storage::fake("images");

        $route = "/posts";

        $response = $this->actingAs($this->user)->post($route, $this->validData);

        $response->assertCreated();
    }

    public function test_unauth_user_cannot_save_post()
    {
        Storage::fake("images");

        $route = "/posts";

        $response = $this->post($route, $this->validData);

        $response->assertStatus(302);
        $response->assertRedirect("login");
    }

    public function test_auth_user_cannot_save_invalid_post()
    {
        Storage::fake("images");

        $route = "/posts";

        $response = $this->actingAs($this->user)->post($route, $this->invalidData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(["title"]);
    }

    private function generatePostData(bool $valid = true)
    {
        $image1 = UploadedFile::fake()->image("test1.png");
        $image2 = UploadedFile::fake()->image("test2.png");
        $images = [$image1, $image2];

        $data = [
            "title" => $valid ? $this->post->title : "",
            "body" => $this->post->body,
            "tags" => "a, b, c",
            "postImages" => $images,
        ];

        return $data;
    }
}
