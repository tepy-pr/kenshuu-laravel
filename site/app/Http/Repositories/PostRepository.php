<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\IPostRepository;
use App\Post;

class PostRepository implements IPostRepository
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function create($validatedPost, $imageModels, $user_id, $imageFolder): Post
    {
        $DEFAULT_IMAGE = $imageFolder . "/default.png";

        $post = Post::create([
            "title" => $validatedPost["title"],
            "body" => $validatedPost["body"],
            "thumbnail" => count($imageModels) > 0 ? $imageModels[0]->url : $DEFAULT_IMAGE,
            "user_id" => $user_id,
        ]);

        return $post;
    }
}
