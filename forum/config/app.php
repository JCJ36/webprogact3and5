<?php
define('APPNAME', 'Re:Edit');
define('URLROOT', 'http://localhost/forum');
define('ONLINE_THRESHOLD', 300);
define('POSTS_PER_PAGE', 15);
define('USERS_PER_PAGE', 20);
date_default_timezone_set('Asia/Manila');


if (session_status() === PHP_SESSION_NONE) session_start();

function setFlash($type, $msg) { $_SESSION['flash'] = ['type'=>$type,'message'=>$msg]; }

function getFlash() {
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;
}

function isLoggedIn() { return isset($_SESSION['user_id']); }
function currentUser() { return $_SESSION['user'] ?? null; }

function hasRole() {
    $roles = func_get_args();
    $user = currentUser();
    return $user && in_array($user['role'], $roles);
}

function requireLogin() {
    if (!isLoggedIn()) {
        setFlash('warning','Please log in first.');
        header('Location: ' . URLROOT . '/index.php?page=login'); exit;
    }
}

function requireRole() {
    requireLogin();
    $roles = func_get_args();
    if (!call_user_func_array('hasRole', $roles)) {
        setFlash('danger','Access denied.');
        header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
    }
}

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function timeAgo($dt) {
    $diff = (new DateTime())->diff(new DateTime($dt));
    if ($diff->y) return $diff->y.'y ago';
    if ($diff->m) return $diff->m.'mo ago';
    if ($diff->d) return $diff->d.'d ago';
    if ($diff->h) return $diff->h.'h ago';
    if ($diff->i) return $diff->i.'m ago';
    return 'just now';
}

function getRoleBadge($role) {
    $b = ['admin'=>'<span class="badge-role badge-admin">Admin</span>',
          'mod'  =>'<span class="badge-role badge-mod">Mod</span>',
          'user' =>'<span class="badge-role badge-user">User</span>'];
    return $b[$role] ?? $b['user'];
}