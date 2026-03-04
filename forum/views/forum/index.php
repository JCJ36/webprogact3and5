<?php require 'views/layouts/header.php'; ?>
<div class="row g-4">

  <!-- Main content -->
  <div class="col-lg-8">
    <!-- Category filter -->
    <div class="d-flex flex-wrap gap-2 mb-3">
      <a href="<?= URLROOT ?>/index.php?page=forum" class="btn btn-sm <?= !$categoryId ? 'btn-dark' : 'btn-outline-secondary' ?>">All</a>
      <?php foreach ($categories as $cat): ?>
      <a href="<?= URLROOT ?>/index.php?page=forum&cat=<?= $cat['id'] ?>"
         class="btn btn-sm <?= $categoryId == $cat['id'] ? 'btn-dark' : 'btn-outline-secondary' ?>">
        <i class="fa-solid <?= e($cat['icon']) ?> me-1"></i><?= e($cat['name']) ?>
        <span class="badge bg-secondary ms-1"><?= $cat['post_count'] ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-bold"><i class="fa-solid fa-comments me-2" style="color:var(--brand)"></i>Forum Posts</h5>
      <a href="<?= URLROOT ?>/index.php?page=forum&action=create" class="btn btn-sm btn-brand fw-semibold">
        <i class="fa-solid fa-plus me-1"></i>New Post
      </a>
    </div>

    <?php if (empty($posts)): ?>
    <div class="text-center py-5 text-muted">
      <i class="fa-solid fa-inbox fa-3x mb-3 d-block"></i>
      <p>No posts yet. Be the first to post!</p>
    </div>
    <?php else: ?>
    <div class="post-list">
      <?php foreach ($posts as $post): ?>
      <div class="post-card <?= $post['is_pinned'] ? 'post-pinned' : '' ?>">
        <div class="d-flex align-items-start gap-3">
          <div class="avatar-circle"><?= strtoupper(substr($post['username'], 0, 2)) ?></div>
          <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
              <?php if ($post['is_pinned']): ?><span class="badge bg-warning text-dark"><i class="fa-solid fa-thumbtack"></i> Pinned</span><?php endif; ?>
              <?php if ($post['is_locked']): ?><span class="badge bg-secondary"><i class="fa-solid fa-lock"></i> Locked</span><?php endif; ?>
              <span class="badge bg-light text-dark border"><i class="fa-solid <?= e($categories[array_search($post['category_id'] ?? 0, array_column($categories,'id'))] ['icon'] ?? 'fa-tag') ?> me-1"></i><?= e($post['category_name']) ?></span>
            </div>
            <a href="<?= URLROOT ?>/index.php?page=forum&action=post&id=<?= $post['id'] ?>" class="post-title">
              <?= e($post['title']) ?>
            </a>
            <div class="post-meta mt-1">
              <?= getRoleBadge($post['user_role']) ?>
              <span class="ms-1"><?= e($post['username']) ?></span>
              <span class="mx-2 text-muted">&bull;</span>
              <i class="fa-regular fa-clock me-1"></i><?= timeAgo($post['created_at']) ?>
              <span class="mx-2 text-muted">&bull;</span>
              <i class="fa-regular fa-comment me-1"></i><?= $post['reply_count'] ?> replies
              <span class="mx-2 text-muted">&bull;</span>
              <i class="fa-regular fa-eye me-1"></i><?= $post['views'] ?> views
            </div>
          </div>
          <?php if (hasRole('mod','admin')): ?>
          <div class="ms-auto">
            <a href="<?= URLROOT ?>/index.php?page=forum&action=delete&id=<?= $post['id'] ?>"
               class="btn btn-outline-danger btn-sm"
               onclick="return confirm('Delete this post and all its replies?')">
              <i class="fa-solid fa-trash"></i>
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page=forum<?= $categoryId ? '&cat='.$categoryId : '' ?>&p=<?= $page-1 ?>">Prev</a></li>
        <?php endif; ?>
        <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <li class="page-item <?= $i==$page?'active':'' ?>">
          <a class="page-link" href="?page=forum<?= $categoryId ? '&cat='.$categoryId : '' ?>&p=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
        <li class="page-item"><a class="page-link" href="?page=forum<?= $categoryId ? '&cat='.$categoryId : '' ?>&p=<?= $page+1 ?>">Next</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
  </div>

  <!-- Sidebar -->
  <div class="col-lg-4">

    <!-- Online Users -->
     <?php require 'views/layouts/online.php'; ?>

    <!-- Recent Posts -->
    <div class="sidebar-card mb-4">
      <div class="sidebar-card-header"><i class="fa-solid fa-clock-rotate-left me-2"></i>Recent Posts</div>
      <div class="sidebar-card-body">
        <?php foreach ($recentPosts as $rp): ?>
        <div class="recent-post-item">
          <a href="<?= URLROOT ?>/index.php?page=forum&action=post&id=<?= $rp['id'] ?>" class="small fw-semibold d-block text-truncate">
            <?= e($rp['title']) ?>
          </a>
          <small class="text-muted">by <?= e($rp['username']) ?> &bull; <?= timeAgo($rp['created_at']) ?></small>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Categories -->
    <div class="sidebar-card">
      <div class="sidebar-card-header"><i class="fa-solid fa-folder me-2"></i>Categories</div>
      <div class="sidebar-card-body p-0">
        <?php foreach ($categories as $cat): ?>
        <a href="<?= URLROOT ?>/index.php?page=forum&cat=<?= $cat['id'] ?>" class="category-link">
          <i class="fa-solid <?= e($cat['icon']) ?> me-2"></i>
          <?= e($cat['name']) ?>
          <span class="badge bg-secondary ms-auto"><?= $cat['post_count'] ?></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>
<?php require 'views/layouts/footer.php'; ?>