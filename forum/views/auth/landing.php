<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= APPNAME ?> &mdash; Community Forum</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/style.css">
</head>
<body class="landing-body">
<?php $flash = getFlash(); ?>
<?php if ($flash): ?>
<div class="alert alert-<?= e($flash['type']) ?> m-3 alert-dismissible fade show">
  <?= $flash['message'] ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="landing-hero">
  <div class="landing-content text-center">
    <div class="landing-icon mb-4">
      <i class="fa-solid fa-headset"></i>
    </div>
    <h1 class="display-3 fw-bold text-white mb-2"><?= APPNAME ?></h1>
    <p class="lead text-white-50 mb-5">Your community. Your conversations. Your forum.</p>
    <div class="d-flex gap-3 justify-content-center">
      <a href="<?= URLROOT ?>/index.php?page=login" class="btn btn-brand btn-lg px-5 fw-semibold">
        <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
      </a>
      <a href="<?= URLROOT ?>/index.php?page=register" class="btn btn-outline-light btn-lg px-5">
        <i class="fa-solid fa-user-plus me-2"></i>Join Now
      </a>
    </div>
    <div class="mt-5 d-flex justify-content-center gap-5 text-white-50">
      <div><i class="fa-solid fa-users fa-2x mb-2 d-block" style="color:var(--brand)"></i><small>Community</small></div>
      <div><i class="fa-solid fa-comments fa-2x mb-2 d-block" style="color:var(--brand)"></i><small>Discussions</small></div>
      <div><i class="fa-solid fa-shield-halved fa-2x mb-2 d-block" style="color:var(--brand)"></i><small>Moderated</small></div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>