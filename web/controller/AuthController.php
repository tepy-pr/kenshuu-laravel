<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";
include_once __DIR__ . "/../models/User.php";

use Controller;
use core\Application;
use core\Request;
use model\User;
use PDO;
use PDOException;

class AuthController extends Controller
{

  public function signUp(Request $request)
  {
    if ($request->isPost()) {
      $body = $request->getBody();
      $userModel = new User();
      $userModel->loadData($body);
      try {
        $hash_password = password_hash($userModel->password, PASSWORD_DEFAULT);
        $new_user_sql = "INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)";
        $new_user = Application::$app->db->pdo->prepare($new_user_sql);
        $new_user->bindValue(":username", $userModel->username);
        $new_user->bindValue(":email", $userModel->email);
        $new_user->bindValue(":password", $hash_password);
        $new_user->execute();

        //get user_id and save in session
        $created_user_sql = "SELECT user_id FROM Users WHERE email = :email";
        $created_user = Application::$app->db->pdo->prepare($created_user_sql);
        $created_user->bindValue(":email", $userModel->email);
        $created_user->execute();
        $user_id = $created_user->fetch(PDO::FETCH_ASSOC)["user_id"];
        Application::$app->session->set("auth_user", $user_id);

        //redirect to homepage
        Application::$app->response->redirect("/");
        return;
      } catch (PDOException $e) {
        echo 'Failed to sign up: ' . $e->getMessage();
        // return;
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
        $user_sql = "SELECT * FROM Users WHERE email = :email";
        $user_stm = Application::$app->db->pdo->prepare($user_sql);
        $user_stm->bindValue(":email", $body["email"]);
        $user_stm->execute();

        $user_data = $user_stm->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
        echo "Error fetching user!";
        return $this->render("user/login", ["error" => TRUE]);
      }
      if ($user_data && password_verify($body["password"], $user_data["password"])) {
        Application::$app->session->set("auth_user", $user_data["user_id"]);
        Application::$app->response->redirect("/", 0);
        // echo "Login Success! Redirecting to homepage in 5 seconds!";
        return;
      } else {
        return $this->render("user/login", ["error" => TRUE]);
      }
    }
    return $this->render("user/login");
  }

  public function logOut()
  {
    Application::$app->session->remove("auth_user");
    Application::$app->response->redirect("/", 0);
  }
}
