<?php

namespace App\Http\Repositories\Interfaces;

use App\Tag;

interface ITagRepository
{
    public function generateTagModels(string $tags): array;
    public function fetchRelatedPosts(Tag $tag): array;
}
