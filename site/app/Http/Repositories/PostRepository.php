<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\IPostRepository;
use App\Post;

class PostRepository implements IPostRepository
{
    protected $imageRepo;
    protected $tagRepo;

    public function __construct(ImageRepository $imageRepo, TagRepository $tagRepo)
    {
        $this->imageRepo = $imageRepo;
        $this->tagRepo = $tagRepo;
    }

    public function create($validatedPost, $user_id): Post
    {
        $imageModels = $this->imageRepo->createImageModelsFromFiles($validatedPost["postImages"] ?? null);
        $tags = $this->tagRepo->generateTagModels($validatedPost["tags"]);

        $DEFAULT_IMAGE = "/images/default.png";

        $post = Post::create([
            "title" => $validatedPost["title"],
            "body" => $validatedPost["body"],
            "thumbnail" => count($imageModels) > 0 ? $imageModels[0]->url : $DEFAULT_IMAGE,
            "user_id" => $user_id,
        ]);
        $post->images()->saveMany($imageModels);
        $post->tags()->saveMany($tags);

        return $post;
    }
}
