<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TagRepository;
use App\Post;
use App\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TagController extends Controller
{

    protected $tagRepo;

    public function __construct(TagRepository $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view("tags.index", ["tags" => $tags]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $posts = $this->tagRepo->fetchRelatedPosts($tag);

        return view("tags.show", ["posts" => $posts]);
    }
}
