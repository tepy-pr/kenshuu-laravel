<?php

namespace model\Service;

include_once __DIR__ . "/../Entities/Image.php";

use core\Application;
use model\Image;
use PDO;
use RuntimeException;

class ImageService
{

  protected $db;

  public function __construct()
  {
    $this->db = Application::$app->db->pdo;
  }

  public function loadUserSelectedImages($inputName, $submitName, $folderName)
  {
    //exif_imagetypeをアクセスできないため（php.iniにextension=exif.soなどを追加しても）
    if (!function_exists('exif_imagetype')) {
      function exif_imagetype($filename)
      {
        if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
          return $type;
        }
        return false;
      }
    }

    $images = [];
    if (isset($_POST[$submitName])) {
      $files = $_FILES[$inputName];
      foreach ($files["name"] as $key => $val) {
        //画像ではなかったら、エラーをThrow
        $this->validateImg($files, $key);

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

  public function validateImg($files, $key)
  {
    if (!isset($files['error'][$key]) || !is_int($files['error'][$key])) {
      throw new RuntimeException('パラメータが不正です');
    }

    switch ($files["error"][$key]) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException('ファイルが選択されていません');
      case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
      case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
        throw new RuntimeException('ファイルサイズが大きすぎます');
      default:
        throw new RuntimeException('その他のエラーが発生しました');
    }

    if (!$this->isImgTypeValid($files, $key)) {
      throw new RuntimeException($files["name"][$key] . "は画像タイプではありません！");
    }
  }

  public function isImgTypeValid($files, $key)
  {
    $allowImgTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    $uploadImgType = exif_imagetype($files['tmp_name'][$key]);
    $isFileAnImage = in_array($uploadImgType, $allowImgTypes);

    return $isFileAnImage;
  }
}
