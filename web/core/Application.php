<?php

namespace core;

require_once(__DIR__ . "/Router.php");
require_once(__DIR__ . "/Request.php");
require_once(__DIR__ . "/Response.php");
require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Session.php");
require_once(__DIR__ . "/../models/User.php");

use core\Router;
use core\Request;
use core\Response;
use core\Database;
use core\Session;
// use model\User;
use PDO;

class Application
{
  public static $ROOT_DIR;

  public $router;
  public $request;
  public $response;
  public $session;
  public $db;
  public static $app;
  public $user;

  public function __construct($rootPath, $config)
  {
    $this->user = null;
    $this->session = new Session();
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
    $this->db = new Database($config["db"]);

    $userId = Application::$app->session->get("auth_user");
    if ($userId) {
      $user_sql = "SELECT * FROM Users WHERE user_id = :user_id";
      $user_stm = Application::$app->db->pdo->prepare($user_sql);
      $user_stm->bindValue(":user_id", intval($userId));
      $user_stm->execute();
      $this->user = $user_stm->fetch(PDO::FETCH_OBJ);
    }
  }

  public function run()
  {
    echo $this->router->resolve();
  }
}
