<?php

namespace controller;

include_once __DIR__ . "/../core/Controller.php";

use Controller;

class SiteController extends Controller
{
  public function home()
  {
    $params = [
      "name" => "Hoho"
    ];
    return $this->render("home", $params);
  }

  public function user()
  {
    return $this->render("user/index");
  }
}
