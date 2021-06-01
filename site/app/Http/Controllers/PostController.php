<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ImageRepository;
use App\Http\Repositories\PostRepository;
use App\Http\Repositories\TagRepository;
use App\Http\Requests\StorePostRequest;
use App\Post;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    protected $postRepo;
    protected $imageRepo;
    protected $tagRepo;

    public function __construct(PostRepository $postRepo, ImageRepository $imageRepo, TagRepository $tagRepo)
    {
        $this->postRepo = $postRepo;
        $this->imageRepo = $imageRepo;
        $this->tagRepo = $tagRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view("posts.index")->with("posts", $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $imageFolder = "/images";

        $validatedPost = $request->validated();
        $files = $request->allFiles();

        $user_id = Auth::id();

        try {
            $imageModels = $this->imageRepo->createImageModelsFromFiles($files, $imageFolder, "postImages");
            $tags = $this->tagRepo->generateTagModels($validatedPost["tags"]);
            $post = $this->postRepo->create($validatedPost, $imageModels, $user_id, $imageFolder);

            $post->images()->saveMany($imageModels);
            $post->tags()->saveMany($tags);
        } catch (Exception $e) {
            return response()->view("posts.edit", ["post" => null, "errorMsg" => "Error saving post!"]);
        }

        return redirect("/", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view("posts.show", ["post" => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $response = Gate::inspect('update', $post);
        if (!$response->allowed()) {
            //return with 403 status code
            return response()->view("posts.edit", ["post" => $post, "errorMsg" => $response->message()], 403);
        }
        return response()->view("posts.edit", ["post" => $post], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $response = Gate::inspect('update', $post);
        if (!$response->allowed()) {
            return response()->view("posts.edit", ["post" => $post, "errorMsg" => $response->message()], 403);
        }


        $post->title = $request["title"];
        $post->body = $request["body"];

        try {
            $post->save();
        } catch (QueryException $e) {
            return response()->view("posts.edit", ["post" => $post, "errorMsg" => "There was an error!"], 400);
        }

        return redirect("/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $response = Gate::inspect('delete', $post);
        if (!$response->allowed()) {
            return redirect("/");
        }

        $post->delete();
        return redirect("/dashboard");
    }
}
