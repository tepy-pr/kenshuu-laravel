<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../core/Model.php";
include_once __DIR__ . "/../models/Entities/User.php";
include_once __DIR__ . "/../models/Services/UserService.php";

use Controller;
use core\Application;
use core\Model;
use core\Request;
use model\Service\UserService;
use model\User;
use PDOException;

class AuthController extends Controller
{

  private $userRepo;

  public function __construct()
  {
    $this->userRepo = new UserService();
  }

  public function signUp(Request $request)
  {
    if ($request->isPost()) {
      $body = $request->getBody();
      $user = new User();
      $user->loadData($body);
      try {
        $hash_password = password_hash($user->password, PASSWORD_DEFAULT);
        $user->password = $hash_password;
        $user->save();
        $user_id = Model::getLastInsertedId()[0];
        Application::$app->session->set("auth_user", $user_id);

        //redirect to homepage
        Application::$app->response->setStatusCode(200);
        Application::$app->response->redirect("/", 0);
        return;
      } catch (PDOException $e) {
        return $this->render("user/signup", ["error" => "Failed to sign up! Please try again."]);
      }
    }
    return $this->render("user/signup");
  }

  public function logIn(Request $request)
  {
    if ($request->isPost()) {
      $body = $request->getBody();
      $user_data = null;
      try {
        $user_data = $this->userRepo->selectBy(["email" => $body["email"]]);
        if ($user_data && $this->userRepo->verifyPassword($body["password"], $user_data["password"])) {
          Application::$app->session->set("auth_user", $user_data["user_id"]);
          Application::$app->response->setStatusCode(200);
          Application::$app->response->redirect("/", 0);
          return;
        }
      } catch (PDOException $e) {
        echo "Error fetching user!";
        return $this->render("user/login", ["error" => TRUE]);
      }
    }
    return $this->render("user/login");
  }

  public function logOut()
  {
    Application::$app->session->remove("auth_user");
    Application::$app->response->setStatusCode(200);
    Application::$app->response->redirect("/", 0);
  }
}
