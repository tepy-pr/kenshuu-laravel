<?php

require __DIR__ . "/core/Application.php";
require __DIR__ . "/controller/SiteController.php";
require __DIR__ . "/controller/AuthController.php";
require __DIR__ . "/controller/PostController.php";
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/vendor/vlucas/phpdotenv/src/Dotenv.php";

use controller\PostController;
use controller\AuthController;
use controller\SiteController;
use core\Application;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
  "db" => [
    "dsn" => $_ENV["DB_DSN"] . ";dbname=" . $_ENV["MYSQL_DATABASE"],
    "user" => $_ENV["MYSQL_USER"],
    "password" => $_ENV["MYSQL_PASSWORD"],
  ]
];

// echo "<pre>";
// var_dump($_ENV["DB_DSN"]);
// var_dump($_ENV["MYSQL_PASSWORD"]);
// // var_dump($_ENV["DB_DSN"]);
// echo "0=====";
// echo "</pre>";

$app = new Application(__DIR__, $config);


$app->router->get("/", [SiteController::class, "home"]);

$app->router->get("/user", [SiteController::class, "user"]);

$app->router->get("/user/signup", [AuthController::class, "signUp"]);
$app->router->post("/user/signup", [AuthController::class, "signUp"]);
$app->router->get("/user/login", [AuthController::class, "logIn"]);
$app->router->post("/user/login", [AuthController::class, "logIn"]);
$app->router->get("/user/logout", [AuthController::class, "logOut"]);

$app->router->get("/post/new", [PostController::class, "new"]);
$app->router->post("/post/new", [PostController::class, "new"]);

$app->run();
