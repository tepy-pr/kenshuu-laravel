<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../models/Services/PostService.php";

use Controller;
use model\Service\PostService;
use PDOException;

class TagController extends Controller
{

  private $postService;

  public function __construct()
  {
    $this->postService = new PostService();
  }

  public function showRelatedPost($request)
  {
    $idParam = $request->getParams("id");
    $tagId = intval($idParam);
    $posts = [];
    try {
      $posts = $this->postService->getAllPostByTagId($tagId);
    } catch (PDOException $e) {
      return $this->render("/tag", ["error" => "Error Loading Posts!"]);
    }

    if (!$posts) {
      return $this->render("/tag", ["error" => "Posts Not Found!"]);
    }
    return $this->render("/tag", ["posts" => $posts]);
  }
}
