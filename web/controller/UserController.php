<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../models/Entities/User.php";
include_once __DIR__ . "/../models/Services/UserService.php";
include_once __DIR__ . "/../models/Services/PostService.php";

use Controller;
use core\Application;
use model\Service\PostService;
use model\Service\UserService;
use PDOException;

class UserController extends Controller
{
  private $userService;
  private $postService;

  public function __construct()
  {
    $this->userService = new UserService();
    $this->postService = new PostService();
  }

  public function dashboard()
  {
    $currentUser = $this->userService->getCurrentUser();
    $posts = [];
    if (!$currentUser) {
      Application::$app->response->redirect("/", 0);
      return;
    }
    try {
      $posts = $this->postService->getAllPostByUserId($currentUser->user_id);
      return $this->render("/user/dashboard", ["posts" => $posts]);
    } catch (PDOException $e) {
      return $this->render("/user/dashboard", ["error" => "Error Loading Posts."]);
    }
  }
}
