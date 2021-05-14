<?php

use core\Application;

?>
<?php if (!Application::$app->user || !$permission) : ?>
  <?php Application::$app->response->redirect("/") ?>
  <h1>You do not have the permission to edit this post. </h1>
  <p>Redirecting to homepage in 5 seconds.</p>
<?php else : ?>
  <h1>Edit Post</h1>
  <form action="/post/edit?id=<?= $post->article_id; ?>" method="post">
    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?= $post->title; ?>" id="title" placeholder="Title Here...">
    </div>
    <div class="mb-3">
      <label for="body" class="form-label">Content</label>
      <textarea class="form-control" name="body" id="body" rows="10"><?= $post->body; ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
  </form>
<?php endif; ?>