<?php

namespace model;

require_once __DIR__ . "/../../core/Model.php";

use core\Model;

class Tag extends Model
{

  public $label;

  public function __construct($label)
  {
    $this->label = strtolower(trim($label));
  }

  public function tableName()
  {
    return "Tags";
  }

  public function attributes()
  {
    return ["label"];
  }
}
