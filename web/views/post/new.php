<?php

use core\Application;

?>
<?php if (!Application::$app->user) : ?>
  <?php Application::$app->response->redirect("/") ?>
  <h1>You must be logged in first to write a post. </h1>
  <p>Redirecting to homepage in 5 seconds.</p>
<?php else : ?>
  <?php if ($error) : ?>
    <p class="error"><?= $error; ?></p>
  <?php endif; ?>
  <form method="post" action="/post/new" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" class="form-control" id="title" placeholder="Title Here...">
      <div>
        <input type="file" name="postImage[]" multiple />
      </div>
      <label for="tags" class="form-label">Tags (Please use "," to separate tags)</label>
      <input type="text" name="tags" class="form-control" id="tags" placeholder="Tags Here...">
    </div>

    <div class="mb-3">
      <label for="body" class="form-label">Content</label>
      <textarea class="form-control" name="body" id="body" rows="10"></textarea>
    </div>

    <button type="submit" name="uploadImage" class="btn btn-primary">Publish</button>
  </form>
<?php endif; ?>