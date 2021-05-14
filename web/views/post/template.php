<?php

use core\Application;

if (!$post) : ?>
  <?php Application::$app->response->redirect("/") ?>
  <p>There is no post. Redirecting to homepage in 5 seconds.</p>
<?php else : ?>
  <h1><?= $post->title; ?></h1>
  <div class="mt-1 mb-2">
    <strong class="block mb-2 text-primary">

      <?php if (count($post->tags) > 1) : ?>
        <?php foreach ($post->tags as $tag) : ?>
          <a href="/tag?id=<?= $tag["tag_id"]; ?>"><?= "#" . $tag["label"]; ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
    </strong>
  </div>
  <div class="my-4">
    <?php foreach ($images as $image) : ?>
      <?php if ($image->url !== "/assets/imgs/default.png") : ?>
        <img src="<?= $image->url; ?>" style="object-fit: cover; height: 200px; width: 200px;" />
      <?php endif; ?>
    <?php endforeach ?>
  </div>
  <p><?= $post->body; ?></p>
<?php endif; ?>