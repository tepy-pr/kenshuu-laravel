<?php

namespace core;

class Model
{
  public function loadData($data)
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        if ($value !== "") {
          $this->{$key} = $value;
        }
      }
    }
  }
}
