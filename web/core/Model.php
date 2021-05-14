<?php

namespace core;

use PDO;

abstract class Model
{
  public abstract function tableName();
  public abstract function attributes();

  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        if ($value !== "") {
          $this->{$key} = $value;
        }
      }
    }
  }

  public function save($unique = FALSE)
  {
    $table = $this->tableName();
    $attrs = $this->attributes();
    $params = [];
    foreach ($attrs as $attr) {
      array_push($params, ":$attr");
    }

    $sql = "";
    if ($unique) {
      $sql = "INSERT IGNORE INTO $table (" . implode(",", $attrs) . ") VALUES (" . implode(",", $params) . ")";
    } else {
      $sql = "INSERT INTO $table (" . implode(",", $attrs) . ") VALUES (" . implode(",", $params) . ")";
    }

    $statement = self::prepare($sql);

    foreach ($attrs as $attr) {
      $statement->bindValue(":$attr", $this->{$attr});
    }

    $statement->execute();
    return true;
  }

  public static function saveMany($models, $unique = false)
  {
    if (!is_array($models) || count($models) == 0) {
      return;
    }
    $isModelSameType = true;
    $modelType = get_class($models[0]);
    $table = $models[0]->tableName();
    $attrs = $models[0]->attributes();

    $paramValues = [];

    foreach ($models as $model) {
      if ($modelType !== get_class($model)) {
        $isModelSameType = false;
        break;
      }
      $paramValue = [];
      foreach ($attrs as $attr) {
        array_push($paramValue,  $model->{$attr});
      }
      array_push($paramValues, $paramValue);
    }

    if (!$isModelSameType) {
      return;
    }

    $allParamValues = [];
    foreach ($paramValues as $paramValue) {
      array_push($allParamValues, "(" . implode(",", $paramValue) . ")");;
    }

    $paramSql = implode(",", $allParamValues);
    $sql = "";
    if ($unique) {
      $sql = "INSERT IGNORE INTO $table (" . implode(",", $attrs) . ") VALUES " . $paramSql;
    } else {
      $sql = "INSERT INTO $table (" . implode(",", $attrs) . ") VALUES " . $paramSql;
    }

    $statement = self::prepare($sql);
    $statement->execute();
    return true;
  }

  public static function prepare($sql)
  {
    return Application::$app->db->pdo->prepare($sql);
  }

  public static function getLastInsertedId()
  {
    $lastIdStm = Application::$app->db->pdo->prepare("SELECT LAST_INSERT_ID();");
    $lastIdStm->execute();
    $id = $lastIdStm->fetch(PDO::FETCH_NUM);
    return $id;
  }
}
