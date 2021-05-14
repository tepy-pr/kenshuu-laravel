<?php

namespace model\Service;

use core\Application;
use core\Model;
use model\Tag;
use PDO;

class PostService
{

  protected $db;

  public function __construct()
  {
    $this->db = Application::$app->db->pdo;
  }

  public function getPostById($id)
  {
    $postSql = "SELECT * FROM Articles WHERE article_id = :post_id";
    $postStm = Application::$app->db->pdo->prepare($postSql);
    $postStm->bindValue(":post_id", $id);
    $postStm->execute();
    $post = $postStm->fetch(PDO::FETCH_OBJ);

    return $post;
  }

  public function updatePost($post)
  {
    $updatePostSql = "UPDATE Articles SET title = :title, body = :body WHERE article_id = :post_id";
    $updatePostStm = $this->db->prepare($updatePostSql);
    $updatePostStm->bindValue(":title", $post->title);
    $updatePostStm->bindValue(":body", $post->body);
    $updatePostStm->bindValue(":post_id", $post->article_id);
    $success = $updatePostStm->execute();

    return $success;
  }

  public function savePost($post)
  {
    $post->save();
    $newPostId = Model::getLastInsertedId();

    return $newPostId[0];
  }

  public function removePostById($id)
  {
    $postSql = "DELETE FROM Articles WHERE article_id = :post_id";
    $postStm = Application::$app->db->pdo->prepare($postSql);
    $postStm->bindValue(":post_id", $id);
    $success = $postStm->execute();
    return $success;
  }

  public function getAllPostByUserId($id)
  {
    $allPostSql = "SELECT * FROM Articles WHERE user_id = $id";
    $allPostStm = Application::$app->db->pdo->prepare($allPostSql);
    $allPostStm->execute();
    $posts = $allPostStm->fetchAll(PDO::FETCH_OBJ);

    return $posts;
  }

  public function getAllPost()
  {
    $allPostSql = "SELECT * FROM Articles";
    $allPostStm = Application::$app->db->pdo->prepare($allPostSql);
    $allPostStm->execute();
    $posts = $allPostStm->fetchAll(PDO::FETCH_OBJ);
    return $posts;
  }

  public function getAllPostByTagId($id)
  {
    $postSQL = "SELECT * FROM Articles WHERE article_id in (SELECT article_id FROM ArticlesTags WHERE tag_id = :tag_id)";
    $postStm = $this->db->prepare($postSQL);
    $postStm->bindValue(":tag_id", $id);
    $postStm->execute();
    $posts = $postStm->fetchAll(PDO::FETCH_OBJ);
    return $posts;
  }
}
