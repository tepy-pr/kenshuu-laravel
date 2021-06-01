<?php

namespace Tests\Unit;

use App\Http\Repositories\TagRepository;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagRepositoryTest extends TestCase
{

    use RefreshDatabase;

    public function test_generate_correct_model()
    {
        $tags = "a, b , c ";

        $tagRepo = new TagRepository(new Tag());
        $tagModels = $tagRepo->generateTagModels($tags);

        $this->assertCount(3, $tagModels);
        $this->assertInstanceOf(Tag::class, $tagModels[0]);
        $this->assertInstanceOf(Tag::class, $tagModels[1]);
        $this->assertInstanceOf(Tag::class, $tagModels[2]);
        $this->assertEquals($tagModels[0]->label, "a");
        $this->assertEquals($tagModels[1]->label, "b");
        $this->assertEquals($tagModels[2]->label, "c");
    }

    public function test_retrieve_related_posts()
    {
        $user = factory(User::class)->create();

        $post1Title = "post1 title";
        $post2Title = "post2 title";
        $post1 = factory(Post::class)->create(["title" => $post1Title, "user_id" => $user]);
        $post2 = factory(Post::class)->create(["title" => $post2Title, "user_id" => $user]);

        $tagRepo = new TagRepository(new Tag());
        $tagsForPost1 = $tagRepo->generateTagModels("tag1, tag2");
        $tagsForPost2 = $tagRepo->generateTagModels("tag2, tag3");

        $post1->tags()->saveMany($tagsForPost1);
        $post2->tags()->saveMany($tagsForPost2);

        $tag1 = $tagsForPost1[0];
        $tag2 = $tagsForPost1[1];

        $tag1RelatedPosts = $tagRepo->fetchRelatedPosts($tag1);
        $tag2RelatedPosts = $tagRepo->fetchRelatedPosts($tag2);

        $this->assertEquals(1, count($tag1RelatedPosts));
        $this->assertEquals($post1Title, $tag1RelatedPosts[0]->title);

        $this->assertEquals(2, count($tag2RelatedPosts));
        $this->assertEquals($post1Title, $tag2RelatedPosts[0]->title);
        $this->assertEquals($post2Title, $tag2RelatedPosts[1]->title);
    }
}
