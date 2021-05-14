<?php

namespace model\Service;

use core\Application;
use PDO;

class UserService
{

  protected $db;

  public function __construct()
  {
    $this->db = Application::$app->db->pdo;
  }

  public function getCurrentUser()
  {
    return Application::$app->user;
  }

  public function selectBy($param)
  {
    $user_data = null;
    foreach ($param as $key => $value) {
      if (!is_int($value)) {
        $value = '"' . $value . '"';
      }
      $user_sql = "SELECT * FROM Users WHERE $key = $value ";
      $user_stm = Application::$app->db->pdo->prepare($user_sql);
      $user_stm->execute();

      $user_data = $user_stm->fetch(PDO::FETCH_ASSOC);
    }

    return $user_data;
  }

  public function verifyPassword($inputPassword, $password)
  {
    return password_verify($inputPassword, $password);
  }
}
