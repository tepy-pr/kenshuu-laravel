<?php

require __DIR__ . "/core/Application.php";
require __DIR__ . "/controller/AuthController.php";
require __DIR__ . "/controller/PostController.php";
require __DIR__ . "/controller/UserController.php";
require __DIR__ . "/controller/TagController.php";
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/vendor/vlucas/phpdotenv/src/Dotenv.php";

use controller\PostController;
use controller\AuthController;
use controller\UserController;
use controller\TagController;
use core\Application;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
  "db" => [
    "dsn" => $_ENV["DB_DSN"] . ";dbname=" . $_ENV["MYSQL_DATABASE"] . ";charset=utf8",
    "user" => $_ENV["MYSQL_USER"],
    "password" => $_ENV["MYSQL_PASSWORD"],
  ]
];

$app = new Application(__DIR__, $config);

$app->router->get("/", [PostController::class, "allPost"]);
$app->router->get("/tag", [TagController::class, "showRelatedPost"]);

$app->router->get("/user/signup", [AuthController::class, "signUp"]);
$app->router->post("/user/signup", [AuthController::class, "signUp"]);
$app->router->get("/user/login", [AuthController::class, "logIn"]);
$app->router->post("/user/login", [AuthController::class, "logIn"]);
$app->router->get("/user/logout", [AuthController::class, "logOut"]);

$app->router->get("/user/dashboard", [UserController::class, "dashboard"]);

$app->router->get("/post/new", [PostController::class, "new"]);
$app->router->post("/post/new", [PostController::class, "new"]);
$app->router->get("/post/edit", [PostController::class, "edit"]);
$app->router->post("/post/edit", [PostController::class, "edit"]);
$app->router->post("/post/delete", [PostController::class, "delete"]);

$app->router->get("/post", [PostController::class, "singlePost"]);

$app->run();
