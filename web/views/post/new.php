<?php

use core\Application;

?>
<?php if (!Application::$app->user) : ?>
  <?php Application::$app->response->redirect("/") ?>
  <h1>You must be logged in first to write a post. </h1>
  <p>Redirecting to homepage in 5 seconds.</p>
<?php else : ?>
  <form action="/post/new" method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" class="form-control" id="title" placeholder="Title Here...">
    </div>
    <div class="mb-3">
      <label for="body" class="form-label">Content</label>
      <textarea class="form-control" name="body" id="body" rows="10"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Publish</button>
  </form>
<?php endif; ?>