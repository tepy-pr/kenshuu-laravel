<?php foreach ($posts as $key => $post) : ?>
  <div class="row mb-2">
    <div class="col-md-6">
      <div class="card flex-md-row mb-4 shadow-sm h-md-250">
        <div class="card-body d-flex flex-column align-items-start">
          <strong class="d-inline-block mb-2 text-primary">
            <?php foreach ($post->tags as $tag) : ?>
              <a href="/tag?id=<?= $tag["tag_id"]; ?>"><?= "#" . $tag["label"]; ?></a>
            <?php endforeach; ?></strong>
          <h3 class="mb-0">
            <a class="text-dark" href="/post?id=<?= $post->article_id; ?>"><?= $post->title; ?></a>
          </h3>
          <div class="card-text mb-auto">
            <?= $post->body; ?>
          </div>
          <a href="/post?id=<?= $post->article_id; ?>">Continue reading</a>
        </div>
        <img class="card-img-right flex-auto d-none d-lg-block" src="<?= $post->thumbnail; ?>" style="object-fit: cover; height: 200px; width: 200px;" alt="<?= $post->title; ?>" />
      </div>
    </div>
  </div>
<?php endforeach; ?>