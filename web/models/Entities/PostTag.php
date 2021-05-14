<?php

namespace model;

require_once __DIR__ . "/../../core/Model.php";

use core\Model;

class PostTag extends Model
{

  public $article_id;
  public $tag_id;

  public function __construct($article_id, $tag_id)
  {
    $this->article_id = $article_id;
    $this->tag_id = $tag_id;
  }

  public function tableName()
  {
    return "ArticlesTags";
  }

  public function attributes()
  {
    return ["article_id", "tag_id"];
  }
}
