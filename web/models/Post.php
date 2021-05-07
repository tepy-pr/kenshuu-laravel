<?php

namespace model;

require_once __DIR__ . "/../core/Model.php";

use core\Model;

class Post extends Model
{
  public $title;
  public $body;
}
