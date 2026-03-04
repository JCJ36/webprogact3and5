<?php
require_once 'config/database.php';
require_once 'config/app.php';

$page = $_GET['page'] ?? 'landing';
$action = $_GET['action'] ?? 'index';

// Update user online status
if (isLoggedIn()) {
    $db = Database::getInstance();
    $uid = (int)$_SESSION['user_id'];
    $now = date('Y-m-d H:i:s');
    $token = session_id();
    $stmt = $db->prepare("INSERT INTO user_sessions (user_id, session_token, last_activity)
        VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE session_token=?, last_activity=?");
    $stmt->bind_param('issss', $uid, $token, $now, $token, $now);
    $stmt->execute();
    $db->query("UPDATE users SET is_online=1, last_seen='" . $now . "' WHERE id=" . $uid);
}

switch ($page) {
    case 'landing':
        if (isLoggedIn()) { header('Location: ' . URLROOT . '/index.php?page=forum'); exit; }
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->landing();
        break;

    case 'login':
        if (isLoggedIn()) { header('Location: ' . URLROOT . '/index.php?page=forum'); exit; }
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->login();
        else $ctrl->showLogin();
        break;

    case 'register':
        if (isLoggedIn()) { header('Location: ' . URLROOT . '/index.php?page=forum'); exit; }
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->register();
        else $ctrl->showRegister();
        break;
        
    case 'logout':
        require_once 'controllers/AuthController.php';
        $ctrl = new AuthController();
        $ctrl->logout();
        break;

    case 'forum':
        requireLogin();
        require_once 'controllers/ForumController.php';
        $ctrl = new ForumController();
        switch ($action) {
            case 'post':    $ctrl->viewPost(); break;
            case 'create':  $ctrl->createPost(); break;
            case 'delete':  $ctrl->deletePost(); break;
            case 'pin':     $ctrl->pinPost(); break;
            case 'reply':   $ctrl->addReply(); break;
            case 'delreply':$ctrl->deleteReply(); break;
            default:        $ctrl->index(); break;
        }
        break;
    case 'admin':
        requireRole('admin');
        require_once 'controllers/AdminController.php';
        $ctrl = new AdminController();
        switch ($action) {
            case 'delete_user': $ctrl->deleteUser(); break;
            default:            $ctrl->dashboard(); break;
        }
        break;
    default:
        header('Location: ' . URLROOT . '/index.php?page=landing');
        exit;
}