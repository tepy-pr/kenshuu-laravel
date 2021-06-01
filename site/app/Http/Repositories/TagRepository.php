<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\ITagRepository;
use App\Post;
use App\Tag;

class TagRepository implements ITagRepository
{
    public $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function generateTagModels(string $tags): array
    {

        $tagModels = [];
        $tagArray = explode(",", $tags);

        foreach ($tagArray as $tag) {
            $tag = trim($tag);
            $newTag = Tag::firstOrCreate(["label" => $tag]);
            array_push($tagModels, $newTag);
        }

        return $tagModels;
    }

    public function fetchRelatedPosts(Tag $tag): array
    {
        /**
         * $post->tagsを利用できていますが、$tag->posts　を利用できないのはわからない
         */
        $posts = [];
        foreach (Post::with("tags")->get() as $post) {
            foreach ($post->tags as $postTag) {
                if ($postTag->tag_id == $tag->tag_id) {
                    array_push($posts, $post);
                }
            }
        }

        return $posts;
    }
}
