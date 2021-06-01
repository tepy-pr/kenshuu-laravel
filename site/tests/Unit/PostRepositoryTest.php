<?php

namespace Tests\Unit;

use App\Http\Repositories\ImageRepository;
use App\Http\Repositories\PostRepository;
use App\Image;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\ImageUtil;

class PostRepositoryTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $imageRepo;
    protected $postRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->postRepo = new PostRepository(new Post());
        $this->imageRepo = new ImageRepository(new Image());
    }

    protected function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    public function test_it_can_create_correct_post()
    {
        $validatedPost = [
            "title" => "title-test",
            "body" => "body-test"
        ];
        $inputName = "postImages";
        $files = ImageUtil::createImageFiles($inputName, true);
        $folder = "/images";

        $imageModels = $this->imageRepo->createImageModelsFromFiles($files, $folder, $inputName);
        $user_id = $this->user->user_id;
        $newPost = $this->postRepo->create($validatedPost, $imageModels, $user_id, $folder);

        $this->assertInstanceOf(Post::class, $newPost);
        $this->assertEquals($user_id, $newPost->user_id);
        $this->assertEquals($validatedPost["title"], $newPost->title);
        $this->assertEquals($validatedPost["body"], $newPost->body);
        $this->assertEquals($imageModels[0]->url, $newPost->thumbnail);
    }

    public function test_it_can_create_correct_post_with_default_thumbnail()
    {
        $validatedPost = [
            "title" => "title-test",
            "body" => "body-test"
        ];
        $imageFolder = "/images";
        $images = [];

        $postRepo = new PostRepository(new Post());
        $newPost = $postRepo->create($validatedPost, $images, $this->user->user_id, $imageFolder);

        $this->assertInstanceOf(Post::class, $newPost);
        $this->assertEquals($this->user->user_id, $newPost->user_id);
        $this->assertEquals($validatedPost["title"], $newPost->title);
        $this->assertEquals($validatedPost["body"], $newPost->body);
        $this->assertEquals($imageFolder . "/default.png", $newPost->thumbnail);
    }
}
