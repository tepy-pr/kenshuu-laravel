<?php

namespace model;

require_once __DIR__ . "/../../core/Model.php";

use core\Model;

class Post extends Model
{
  public $title;
  public $body;
  public $thumbnail;
  public $user_id;
  public $tags;

  public function tableName()
  {
    return "Articles";
  }

  public function attributes()
  {
    return ["title", "body", "thumbnail", "user_id"];
  }
}
