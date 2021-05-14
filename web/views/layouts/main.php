<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

  <style>
    a {
      text-decoration: none;
    }

    ul {
      list-style: none;
    }
  </style>

  <title>Hello, world!</title>
</head>

<body>
  <div class="container py-3">
    <nav class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4">
        <a href="/">Home</a>
      </div>
      <div class="navbar-brand col-4 text-center">
        <a class="text-dark" href="/">
          KIJI
        </a>
      </div>
      <ul class="col-4 d-flex justify-content-end align-items-center">
        <?php

        use core\Application;
        ?>
        <?php if (Application::$app->user) : ?>
          <li class="nav-item btn btn-sm btn-outline-primary">
            <a class="nav-link" href="/post/new">Create Post</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/logout">Log Out</a>
          </li>
        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link btn btn-sm btn-outline-primary" href="/user/signup">Sign Up</a>
          </li>
          <li class="nav-item ms-4">
            <a class="nav-link btn btn-sm btn-outline-secondary" href="/user/login">Log In</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
  <div class="container">

    {{content}}
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
</body>

</html>