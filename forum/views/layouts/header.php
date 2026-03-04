<?php $flash = getFlash(); $user = currentUser(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? APPNAME) ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="<?= URLROOT ?>/index.php?page=forum">
        <i class="fa-solid fa-headset me-2" style="color: var(--brand)" ></i><?= APPNAME ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="<?= URLROOT ?>/index.php?page=forum"><i class="fa-solid fa-home me-1"></i>Forum</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URLROOT ?>/index.php?page=forum&action=create"><i class="fa-solid fa-plus me-1"></i>New Post</a>
        </li>
        <?php if (hasRole('admin')): ?>
        <li class="nav-item">
          <a class="nav-link" style="color:var(--brand)" href="<?= URLROOT ?>/index.php?page=admin"><i class="fa-solid fa-shield-halved me-1"></i>Admin</a>
        </li>
        <?php endif; ?>
      </ul>
      <?php if ($user): ?>
      <div class="d-flex align-items-center gap-2">
        <?= getRoleBadge($user['role']) ?>
        <span class="text-light fw-semibold"><i class="fa-solid fa-user me-1"></i><?= e($user['username']) ?></span>
        <a href="<?= URLROOT ?>/index.php?page=logout" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container-fluid px-4 mt-3">
<?php if ($flash): ?>
<div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
  <?= $flash['message'] ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>