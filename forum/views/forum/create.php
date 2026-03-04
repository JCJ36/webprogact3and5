<?php require 'views/layouts/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="create-post-card">
      <h4 class="fw-bold mb-4"><i class="fa-solid fa-pen-to-square me-2" style="color:var(--brand)"></i>Create New Post</h4>
      <form method="POST" action="<?= URLROOT ?>/index.php?page=forum&action=create">
        <div class="mb-3">
          <label class="form-label fw-semibold">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">-- Select a category --</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Post Title</label>
          <input type="text" name="title" class="form-control" placeholder="Enter a descriptive title..." maxlength="255" required>
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold">Content</label>
          <textarea name="content" class="form-control" rows="10" placeholder="Write your post here..." required></textarea>
        </div>
        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-brand fw-semibold px-4">
            <i class="fa-solid fa-paper-plane me-2"></i>Publish Post
          </button>
          <a href="<?= URLROOT ?>/index.php?page=forum" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require 'views/layouts/footer.php'; ?>