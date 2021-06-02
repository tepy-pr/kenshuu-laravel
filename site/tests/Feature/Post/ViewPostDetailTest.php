<?php

namespace Tests\Feature\Post;

use App\Http\Repositories\ImageRepository;
use App\Image;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\ImageUtil;

class ViewPostDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_user_can_see_post_detail()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create(["thumbnail" => "/images/test.png"]);

        $imageFiles = ImageUtil::createImageFiles();

        $imageRepo = new ImageRepository(new Image());
        $imageModels = $imageRepo->createImageModelsFromFiles($imageFiles);

        $post->images()->saveMany($imageModels);

        $response = $this->actingAs($user)->get('/posts/' . $post->post_id);

        $response->assertStatus(200);
        $response->assertSee(e($post->title));
        $response->assertSee(e($post->images[0]->url));
        $response->assertSee(e($post->images[1]->url));
    }

    public function test_unauth_user_can_see_post_detail()
    {
        $post = factory(Post::class)->create();

        $response = $this->get('/posts/' . $post->post_id);

        $response->assertStatus(200);
        $response->assertSee(e($post->title));
    }
}
