<?php

namespace model\Service;

include_once __DIR__ . "/../Entities/Image.php";

use core\Application;
use model\Image;
use PDO;

class ImageService
{

  protected $db;

  public function __construct()
  {
    $this->db = Application::$app->db->pdo;
  }

  public function loadUserSelectedImages($inputName, $submitName, $folderName)
  {
    $images = [];
    if (isset($_POST[$submitName])) {
      foreach ($_FILES[$inputName]["name"] as $key => $val) {
        $image = new Image(null, $_FILES[$inputName]["name"][$key], $_FILES[$inputName]["tmp_name"][$key], $folderName);
        array_push($images, $image);
      }
    }
    return $images;
  }

  public function uploadImages($images, $newPostId)
  {
    foreach ($images as $image) {
      $image->setArticleId($newPostId);
      $image->save();
      $image->moveFiles();
    }
  }

  public function getImagesByPostId($id)
  {
    $imagesSql = "SELECT url FROM Images WHERE article_id = :post_id";
    $imageStm = $this->db->prepare($imagesSql);
    $imageStm->bindValue(":post_id", $id);
    $imageStm->execute();
    $images = $imageStm->fetchAll(PDO::FETCH_OBJ);

    return $images;
  }
}
