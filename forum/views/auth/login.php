<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login &mdash; <?= APPNAME ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/style.css">
</head>
<body class="auth-body">
<?php $flash = getFlash(); ?>
<div class="auth-wrapper">
  <div class="auth-card shadow-lg">
    <div class="auth-header text-center mb-4">
      <a href="<?= URLROOT ?>/index.php?page=landing" class="text-decoration-none">
        <i class="fa-solid fa-fire-flame-curved fa-3x mb-2" style="color:var(--brand)"></i>
        <h2 class="fw-bold text-dark"><?= APPNAME ?></h2>
      </a>
      <p class="text-muted">Welcome back! Sign in to continue.</p>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible">
      <?= $flash['message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= URLROOT ?>/index.php?page=login">
      <div class="mb-3">
        <label class="form-label fw-semibold">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
          <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Your password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold">
        <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
      </button>
    </form>

    <hr>
    <p class="text-center text-muted mb-0">
      Don't have an account?
      <a href="<?= URLROOT ?>/index.php?page=register" class="fw-semibold text-dark">Register here</a>
    </p>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>