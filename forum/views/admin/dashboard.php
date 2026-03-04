<?php require 'views/layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3 class="fw-bold mb-0"><i class="fa-solid fa-shield-halved me-2" style="color:var(--brand)"></i>Admin Dashboard</h3>
</div>

<!-- Stats row -->
<div class="row g-3 mb-5">
  <div class="col-sm-4">
    <div class="admin-stat-card">
      <div class="admin-stat-icon bg-primary"><i class="fa-solid fa-users"></i></div>
      <div>
        <div class="admin-stat-num"><?= $total ?></div>
        <div class="admin-stat-label">Total Users</div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="admin-stat-card">
      <div class="admin-stat-icon bg-warning"><i class="fa-solid fa-comments"></i></div>
      <div>
        <div class="admin-stat-num"><?= $totalPosts ?></div>
        <div class="admin-stat-label">Total Posts</div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="admin-stat-card">
      <div class="admin-stat-icon bg-success"><i class="fa-solid fa-circle"></i></div>
      <div>
        <div class="admin-stat-num"><?= $onlineCount ?></div>
        <div class="admin-stat-label">Online Now</div>
      </div>
    </div>
  </div>
</div>

<!-- Users table -->
<div class="admin-table-card">
  <div class="admin-table-header">
    <i class="fa-solid fa-users me-2"></i>User Management
    <span class="ms-2 text-muted fw-normal">(<?= $total ?> total)</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover admin-table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr class="<?= $u['id'] == $_SESSION['user_id'] ? 'table-warning' : '' ?>">
          <td class="text-muted"><?= $u['id'] ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="avatar-sm-table"><?= strtoupper(substr($u['username'],0,2)) ?></div>
              <strong><?= e($u['username']) ?></strong>
              <?php if ($u['id'] == $_SESSION['user_id']): ?>
              <span class="badge bg-secondary">You</span>
              <?php endif; ?>
            </div>
          </td>
          <td><?= e($u['email']) ?></td>
          <td><?= getRoleBadge($u['role']) ?></td>
          <td>
            <?php if ($u['is_online']): ?>
            <span class="badge bg-success"><i class="fa-solid fa-circle me-1" style="font-size:.5rem"></i>Online</span>
            <?php else: ?>
            <span class="badge bg-secondary">Offline</span>
            <?php endif; ?>
          </td>
          <td><small class="text-muted"><?= date('M d, Y', strtotime($u['created_at'])) ?></small></td>
          <td>
            <?php if ($u['id'] != $_SESSION['user_id'] && $u['role'] !== 'admin'): ?>
            <a href="<?= URLROOT ?>/index.php?page=admin&action=delete_user&id=<?= $u['id'] ?>"
               class="btn btn-outline-danger btn-xs"
               onclick="return confirm('Delete user <?= e($u['username']) ?> and all their posts?')">
              <i class="fa-solid fa-trash me-1"></i>Delete
            </a>
            <?php else: ?>
            <span class="text-muted small">Protected</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div class="p-3">
    <nav>
      <ul class="pagination pagination-sm justify-content-center mb-0">
        <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <li class="page-item <?= $i==$page?'active':'' ?>">
          <a class="page-link" href="?page=admin&p=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>
  <?php endif; ?>
</div>
<?php require 'views/layouts/footer.php'; ?>