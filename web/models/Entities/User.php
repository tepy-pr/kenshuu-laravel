<?php

namespace model;

require_once __DIR__ . "/../../core/Model.php";

use core\Model;

class User extends Model
{

  public $username;
  public $email;
  public $password;

  public function tableName()
  {
    return "Users";
  }

  public function attributes()
  {
    return ["username", "email", "password"];
  }
}
