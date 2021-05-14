<?php foreach ($posts as $key => $post) : ?>
  <div class="row mb-2">
    <div class="col-md-6">
      <div class="card flex-md-row mb-4 shadow-sm h-md-250">
        <div class="card-body d-flex flex-column align-items-start">
          <h3 class="mb-0">
            <a class="text-dark" href="/post?id=<?= $post->article_id; ?>"><?= $post->title; ?></a>
          </h3>
          <div class="card-text mb-auto">
            <?= $post->body; ?>
          </div>
          <div class="d-flex">
            <a class="btn btn-sm btn-outline-primary" href="/post/edit?id=<?= $post->article_id; ?>">
              Edit
            </a>
            <form action="/post/delete?id=<?= $post->article_id; ?>" method="post">
              <button class="btn btn-sm btn-outline-danger text-danger ms-2" type="submit">Delete</button>
            </form>
          </div>
        </div>
        <img class="card-img-right flex-auto d-none d-lg-block" src="<?= $post->thumbnail; ?>" style="object-fit: cover; height: 200px; width: 200px;" alt="<?= $post->title; ?>" />
      </div>
    </div>
  </div>
<?php endforeach; ?>