<?php

namespace core;

class Response
{
  public function setStatusCode($code)
  {
    http_response_code($code);
  }

  public function redirect($url, $delay = 5)
  {
    header("refresh: $delay;url=$url");
  }
}
