<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../models/User.php";
include_once __DIR__ . "/../models/Post.php";

use Controller;
use core\Application;
use core\Request;
use model\Post;
use model\User;
use PDO;
use PDOException;

class PostController extends Controller
{

  public function new(Request $request)
  {
    if ($request->isPost()) {
      $body = $request->getBody();
      $newPost = new Post();
      $newPost->loadData($body);

      var_dump($newPost);
    }
    return $this->render("/post/new");
  }
}
