<?php

namespace Tests\Unit;

use App\Http\Repositories\ImageRepository;
use App\Http\Repositories\PostRepository;
use App\Http\Repositories\TagRepository;
use App\Image;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\ImageUtil;
use Mockery as m;

class PostRepositoryTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $imageRepoMock;
    protected $tagRepoMock;
    protected $postRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageRepoMock = m::mock(ImageRepository::class);
        $this->tagRepoMock = m::mock(TagRepository::class);

        $this->user = factory(User::class)->create();
        $this->postRepo = new PostRepository($this->imageRepoMock, $this->tagRepoMock);
    }

    protected function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }

    public function test_it_can_create_correct_post()
    {
        $user_id = $this->user->user_id;

        $imageFiles = ImageUtil::createImageFiles();

        $validatedPost = [
            "title" => "title-test",
            "body" => "body-test",
            "tags" => "a, b, c",
            "postImages" => $imageFiles
        ];

        $this->imageRepoMock->shouldReceive("createImageModelsFromFiles")->with($validatedPost["postImages"])->once();
        $this->tagRepoMock->shouldReceive("generateTagModels")->with($validatedPost["tags"])->once();

        $newPost = $this->postRepo->create($validatedPost, $user_id);

        $this->assertInstanceOf(Post::class, $newPost);
        $this->assertEquals($user_id, $newPost->user_id);
        $this->assertEquals($validatedPost["title"], $newPost->title);
        $this->assertEquals($validatedPost["body"], $newPost->body);
        $this->assertNotNull($newPost->thumbnail);
    }

    public function test_it_can_create_correct_post_with_default_thumbnail()
    {
        $user_id = $this->user->user_id;

        $validatedPost = [
            "title" => "title-test",
            "body" => "body-test",
            "tags" => "a, b, c"
        ];

        $this->imageRepoMock->shouldReceive("createImageModelsFromFiles")->with(null)->once();
        $this->tagRepoMock->shouldReceive("generateTagModels")->with($validatedPost["tags"])->once();

        $newPost = $this->postRepo->create($validatedPost, $user_id);

        $this->assertInstanceOf(Post::class, $newPost);
        $this->assertEquals($this->user->user_id, $newPost->user_id);
        $this->assertEquals($validatedPost["title"], $newPost->title);
        $this->assertEquals($validatedPost["body"], $newPost->body);
        $this->assertEquals("/images/default.png", $newPost->thumbnail);
    }
}
