<?php

namespace core;

class Router
{
  protected $routes = [];
  protected $request;
  protected $response;

  public function __construct($request, $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function get($path, $callback)
  {
    $this->routes["get"][$path] = $callback;
  }

  public function post($path, $callback)
  {
    $this->routes["post"][$path] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = strtolower($this->request->getMethod());
    $callback = $this->routes[$method][$path] ?? false;

    if ($callback === false) {
      Application::$app->response->setStatusCode(404);
      return $this->renderView("_404");
    }

    if (is_string($callback)) {
      return $this->renderView($callback);
    }
    if (is_array($callback)) {
      $callback[0] = new $callback[0]();
    }
    //give $this->request to the callback
    return call_user_func($callback, $this->request);
  }

  public function renderView($view, $params = [])
  {
    $layoutContent = $this->getLayoutContent();
    $viewContent = $this->getViewContent($view, $params);
    return str_replace("{{content}}", $viewContent, $layoutContent);
  }

  protected function getLayoutContent()
  {
    ob_start();
    include_once Application::$ROOT_DIR . "/views/layouts/main.php";
    return ob_get_clean();
  }

  protected function getViewContent($view, $params)
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }

    ob_start();
    include_once Application::$ROOT_DIR . "/views/$view.php";
    return ob_get_clean();
  }
}
