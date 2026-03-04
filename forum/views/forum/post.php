<?php require 'views/layouts/header.php'; ?>
<div class="row g-4">
  <div class="col-lg-8">

    <!-- Post -->
    <div class="post-full-card mb-4">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <span class="badge bg-light text-dark border mb-2"><?= e($post['category_name']) ?></span>
          <h2 class="fw-bold mb-0"><?= e($post['title']) ?></h2>
        </div>
        <div>
            <?php if (hasRole('mod','admin')): ?>
                <?php if ($post['is_pinned']): ?>
                <a href="<?= URLROOT ?>/index.php?page=forum&action=pin&id=<?= $post['id'] ?>"
                class="btn btn-outline-dark btn-sm ms-3"
                onclick="return confirm('Unpin this post?')">
                    <i class="fa-solid fa-thumbtack-slash me-1"></i>Unpin Post
                </a>
                <?php else: ?>
                    <a href="<?= URLROOT ?>/index.php?page=forum&action=pin&id=<?= $post['id'] ?>"
                class="btn btn-outline-dark btn-sm ms-3"
                onclick="return confirm('Pin this post?')">
                    <i class="fa-solid fa-thumbtack me-1"></i>Pin Post
                </a>
                <?php endif; ?>
            <a href="<?= URLROOT ?>/index.php?page=forum&action=delete&id=<?= $post['id'] ?>"
            class="btn btn-outline-danger btn-sm ms-3"
            onclick="return confirm('Delete this post?')">
            <i class="fa-solid fa-trash me-1"></i>Delete Post
            </a>
            <?php endif; ?>
        </div> 
      </div>

      <div class="d-flex align-items-center gap-2 mb-3 post-meta">
        <div class="avatar-circle"><?= strtoupper(substr($post['username'],0,2)) ?></div>
        <?= getRoleBadge($post['user_role']) ?>
        <strong><?= e($post['username']) ?></strong>
        <span class="text-muted">&bull;</span>
        <span><?= timeAgo($post['created_at']) ?></span>
        <span class="text-muted">&bull;</span>
        <i class="fa-regular fa-eye me-1"></i><?= $post['views'] ?> views
      </div>

      <div class="post-body"><?= nl2br(e($post['content'])) ?></div>
    </div>

    <!-- Replies -->
    <h5 class="fw-bold mb-3" id="replies">
      <i class="fa-solid fa-comments me-2" style="color:var(--brand)"></i>
      Replies <span class="text-muted fw-normal">(<?= count($replies) ?>)</span>
    </h5>

    <?php if (empty($replies)): ?>
    <p class="text-muted">No replies yet. Be the first to reply!</p>
    <?php else: ?>
    <?php foreach ($replies as $reply): ?>
    <div class="reply-card mb-3">
      <div class="d-flex gap-3">
        <div class="avatar-circle avatar-sm-circle"><?= strtoupper(substr($reply['username'],0,2)) ?></div>
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="d-flex align-items-center gap-2">
              <?= getRoleBadge($reply['user_role']) ?>
              <strong><?= e($reply['username']) ?></strong>
              <small class="text-muted"><?= timeAgo($reply['created_at']) ?></small>
            </div>
            <?php if (hasRole('mod','admin')): ?>
            <a href="<?= URLROOT ?>/index.php?page=forum&action=delreply&id=<?= $reply['id'] ?>&post_id=<?= $post['id'] ?>"
               class="btn btn-outline-danger btn-xs"
               onclick="return confirm('Delete this reply?')">
              <i class="fa-solid fa-trash"></i>
            </a>
            <?php endif; ?>
          </div>
          <p class="mb-0"><?= nl2br(e($reply['content'])) ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- Reply form -->
    <?php if (!$post['is_locked']): ?>
    <div class="reply-form-card mt-4">
      <h6 class="fw-bold mb-3"><i class="fa-solid fa-reply me-2"></i>Post a Reply</h6>
      <form method="POST" action="<?= URLROOT ?>/index.php?page=forum&action=reply">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <textarea name="content" class="form-control mb-3" rows="4" placeholder="Write your reply..." required></textarea>
        <button type="submit" class="btn btn-brand fw-semibold">
          <i class="fa-solid fa-paper-plane me-2"></i>Post Reply
        </button>
      </form>
    </div>
    <?php else: ?>
    <div class="alert alert-secondary mt-4"><i class="fa-solid fa-lock me-2"></i>This thread is locked.</div>
    <?php endif; ?>

  </div>

  <!-- Sidebar -->
  <div class="col-lg-4">
    <?php require 'views/layouts/online.php'; ?>

    <div class="sidebar-card">
      <div class="sidebar-card-header"><i class="fa-solid fa-info-circle me-2"></i>Thread Info</div>
      <div class="sidebar-card-body">
        <div class="thread-stat"><i class="fa-regular fa-eye me-2 text-muted"></i><?= $post['views'] ?> views</div>
        <div class="thread-stat"><i class="fa-regular fa-comment me-2 text-muted"></i><?= count($replies) ?> replies</div>
        <div class="thread-stat"><i class="fa-regular fa-clock me-2 text-muted"></i><?= timeAgo($post['created_at']) ?></div>
        <div class="mt-3">
          <a href="<?= URLROOT ?>/index.php?page=forum" class="btn btn-sm btn-outline-dark w-100">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Forum
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require 'views/layouts/footer.php'; ?>