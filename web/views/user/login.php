<?php

use core\Application;

?>
<?php if (Application::$app->user) : ?>
  <?php Application::$app->response->redirect("/") ?>
  <h1>You already logged in. </h1>
  <p>Redirecting to homepage in 5 seconds.</p>
<?php else : ?>
  <h1>Login</h1>
  <form action="/user/login" method="post">
    <div class="mb-3">
      <label for="email">Email</label>
      <input type="email" id="email" class="form-control" name="email">
    </div>
    <div class="mb-3">
      <label for="password">Password</label>
      <input type="password" id="password" class="form-control" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Log In</button>
  </form>
  <?php if ($error) : ?>
    <div class="alert alert-danger d-flex align-items-center" role="alert">
      <div>
        Login Failed! Please Try Again!
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>