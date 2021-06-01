<?php

namespace App\Http\Repositories\Interfaces;

use App\Post;

interface IPostRepository
{
    public function create($validatedPost, $imageModels, $user_id, $imageFolder): Post;
}
