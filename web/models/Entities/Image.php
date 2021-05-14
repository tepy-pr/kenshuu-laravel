<?php

namespace model;

require_once __DIR__ . "/../../core/Model.php";

use core\Application;
use core\Model;

class Image extends Model
{

  public $url;
  public $article_id;
  public $filename;
  public $tempname;

  public function __construct($article_id = NULL, $filename, $tempname, $tempFolder)
  {
    if ($article_id) {
      if (is_int($article_id)) {
        $this->article_id = $article_id;
      } else {
        $this->article_id = intval($article_id);
      }
    }
    $this->filename = $filename;
    $this->tempname = $tempname;
    if (!$this->filename) {
      $this->url = "/assets/imgs/default.png";
    } else {
      $this->url = "/" . $tempFolder . "/" . $this->filename;
    }
  }

  public function tableName()
  {
    return "Images";
  }

  public function attributes()
  {
    return ["url", "article_id"];
  }

  public function setArticleId($article_id)
  {
    $this->article_id = $article_id;
  }

  public function moveFiles()
  {
    move_uploaded_file($this->tempname, Application::$ROOT_DIR . $this->url);
  }
}
