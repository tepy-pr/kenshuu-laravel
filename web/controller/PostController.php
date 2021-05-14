<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../models/Entities/User.php";
include_once __DIR__ . "/../models/Entities/Post.php";
include_once __DIR__ . "/../models/Entities/Image.php";
include_once __DIR__ . "/../models/Entities/Tag.php";
include_once __DIR__ . "/../models/Entities/PostTag.php";
include_once __DIR__ . "/../models/Services/UserService.php";
include_once __DIR__ . "/../models/Services/PostService.php";
include_once __DIR__ . "/../models/Services/ImageService.php";
include_once __DIR__ . "/../models/Services/TagService.php";

use Controller;
use core\Application;
use core\Request;
use model\Post;
use model\Service\ImageService;
use model\Service\PostService;
use model\Service\UserService;
use model\Service\TagService;
use PDO;
use PDOException;

class PostController extends Controller
{

  private $userService;
  private $postService;
  private $imageService;
  private $tagService;

  public function __construct()
  {
    $this->userService = new UserService();
    $this->postService = new PostService();
    $this->imageService = new ImageService();
    $this->tagService = new TagService();
  }

  public function new(Request $request)
  {
    if ($request->isPost()) {
      $currentUser = $this->userService->getCurrentUser();

      $images = $this->imageService->loadUserSelectedImages("postImage", "uploadImage", "image");

      if ($currentUser) {
        try {

          $body = $request->getBody();
          $post = new Post();
          $post->loadData($body);
          $post->user_id = intval($currentUser->user_id);
          $post->thumbnail = $images[0]->url;

          $rawTags = explode(",", trim($body["tags"]));
          $cleanTagStrings = $this->tagService->getCleanTagStrings($rawTags);

          Application::$app->db->pdo->beginTransaction();

          $postId = $this->postService->savePost($post);
          $this->imageService->uploadImages($images, $postId);
          $this->tagService->saveTags($cleanTagStrings);
          $insertedTags = $this->tagService->getInsertedTags($cleanTagStrings);
          $this->tagService->savePostTags($insertedTags, $postId);

          Application::$app->db->pdo->commit();
          Application::$app->response->setStatusCode(200);
          Application::$app->response->redirect("/post", 0);
        } catch (PDOException $e) {
          Application::$app->db->pdo->rollBack();
          return $this->render("/post/new", ["error" => "Failed to create a new post!" . $e]);
        }
      }
    }
    return $this->render("/post/new");
  }

  public function allPost()
  {
    $posts = [];

    try {
      $posts = $this->postService->getAllPost();
      foreach ($posts as $post) {
        $tags = $this->tagService->getTagsByPostId($post->article_id);
        $post->tags = $tags;
      }
    } catch (PDOException $e) {
    }
    return $this->render("/post/index", ["posts" => $posts]);
  }

  public function singlePost($request)
  {
    $postId = $request->getBody()["id"];
    // if there is no id in the url ("/post"), display all posts
    if (!$postId) {
      return $this->allPost();
    }

    $post = $this->postService->getPostById($postId);
    $images = $this->imageService->getImagesByPostId($postId);
    $tags = $this->tagService->getTagsByPostId($postId);
    $post->tags = $tags;

    if (!$post) {
      return $this->render("/post/template", ["error" => "No Post Found!"]);
    }
    return $this->render("/post/template", ["post" => $post, "images" => $images]);
  }

  public function edit($request)
  {
    if ($request->isGet()) {
      $postId = $request->getBody()["id"];
      $canEdit = FALSE;
      // if there is no id in the url ("/post"), display all posts
      if (!$postId) {
        return $this->render("/post/edit", ["error" => TRUE]);
      }
      try {
        $post = $this->postService->getPostById($postId);
        if ($post->user_id === Application::$app->user->user_id) {
          $canEdit = TRUE;
        }
        return $this->render("/post/edit", ["post" => $post, "permission" => $canEdit]);
      } catch (PDOException $e) {
        return $this->render("/post/edit", ["error" => TRUE]);
      }
    }

    if ($request->isPost()) {
      $postData = $request->getBody();
      $post = new Post();
      $post->loadData($postData);

      $idParam = $request->getParams("id");
      $postId = intval($idParam);
      $post->article_id = $postId;

      try {
        $this->postService->updatePost($post);
        Application::$app->response->setStatusCode(200);
        Application::$app->response->redirect("/post", 0);
        return;
      } catch (PDOException $e) {
        return $this->render("/post/template", ["error" => TRUE]);
      }
    }
  }

  public function delete($request)
  {
    $canDelete = FALSE;
    if ($request->isPost()) {

      $idParam = $request->getParams("id");
      $postId = intval($idParam);

      if (!$postId) {
        return $this->render("/404");
      }
      try {
        $post = $this->postService->getPostById($postId);
        $currentUser = $this->userService->getCurrentUser();
        if ($post->user_id === $currentUser->user_id) {
          $canDelete = TRUE;
        }

        if ($canDelete) {

          Application::$app->db->pdo->beginTransaction();

          $this->tagService->removeTagByPostId($postId);
          $this->postService->removePostById($postId);

          Application::$app->db->pdo->commit();
          Application::$app->response->setStatusCode(200);
          Application::$app->response->redirect("/user/dashboard", 0);
          return;
        } else {
          Application::$app->response->setStatusCode(403);
          Application::$app->response->redirect("/", 0);
        }
      } catch (PDOException $e) {
        return $this->render("/post/delete", ["error" => TRUE]);
      }

      Application::$app->response->setStatusCode(404);
      return $this->render("/404");
    }
  }
}
