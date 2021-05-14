<?php

namespace model\Service;

use core\Application;
use core\Model;
use model\PostTag;
use model\Tag;
use PDO;

class TagService
{

  protected $db;

  public function __construct()
  {
    $this->db = Application::$app->db->pdo;
  }

  public function getCleanTagStrings($rawTags)
  {
    $cleanTagStrings = [];
    foreach ($rawTags as $tagLabel) {
      array_push($cleanTagStrings, '"' . trim($tagLabel) . '"');
    }

    return $cleanTagStrings;
  }

  public function saveTags($cleanTagStrings)
  {
    $tags = [];

    foreach ($cleanTagStrings as $cleanLabel) {
      $tag = new Tag($cleanLabel);
      array_push($tags, $tag);
    }

    Model::saveMany($tags, true);
  }

  public function getInsertedTags($cleanTagStrings)
  {
    $findTagSQL = "SELECT * FROM Tags WHERE label in (" . implode(",", $cleanTagStrings) . ")";
    $findTagStm = $this->db->prepare($findTagSQL);
    $findTagStm->execute();
    $insertedTags = $findTagStm->fetchAll(PDO::FETCH_ASSOC);

    return $insertedTags;
  }


  public function savePostTags($insertedTags, $postId)
  {
    $postTags = [];
    foreach ($insertedTags as $insertedTag) {
      $id = intval($insertedTag["tag_id"]);
      $postTag = new PostTag($postId, $id);
      array_push($postTags, $postTag);
    }

    Model::saveMany($postTags);
  }

  public function getTagsByPostId($postId)
  {
    $tagSql = "SELECT * FROM Tags WHERE tag_id in (SELECT tag_id FROM ArticlesTags WHERE article_id = " . $postId . " )";
    $tagStm = $this->db->prepare($tagSql);
    $tagStm->execute();

    $tags = $tagStm->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
  }

  public function removeTagByPostId($id)
  {
    $tagSql = "DELETE FROM ArticlesTags WHERE article_id = :post_id";
    $tagStm = $this->db->prepare($tagSql);
    $tagStm->bindValue(":post_id", $id);
    $success = $tagStm->execute();

    return $success;
  }
}
