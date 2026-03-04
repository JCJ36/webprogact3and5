<div class="sidebar-card mb-4">
    <div class="sidebar-card-header">
    <i class="fa-solid fa-circle text-success me-2" style="font-size:.6rem"></i>
    Online Users <span class="badge bg-success ms-1"><?= count($onlineUsers) ?></span>
    </div>
    <div class="sidebar-card-body">
    <?php if (empty($onlineUsers)): ?>
    <p class="text-muted small mb-0">No users online right now.</p>
    <?php else: ?>
    <?php foreach ($onlineUsers as $ou): ?>
    <div class="online-user-item">
        <span class="online-dot"></span>
        <div class="avatar-sm"><?= strtoupper(substr($ou['username'],0,1)) ?></div>
        <span class="ms-2 me-2 small fw-semibold"><?= e($ou['username']) ?></span>
        <?= getRoleBadge($ou['role']) ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>