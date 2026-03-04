<?php
class AdminController {
    private $userModel;
    private $postModel;

    public function __construct() {
        require_once 'models/User.php';
        require_once 'models/Post.php';
        $this->userModel = new User();
        $this->postModel = new Post();
    }

    public function dashboard() {
        $page       = max(1, (int)($_GET['p'] ?? 1));
        $perPage    = USERS_PER_PAGE;
        $total      = $this->userModel->countAll();
        $offset     = ($page - 1) * $perPage;
        $users      = $this->userModel->getAll($perPage, $offset);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $totalPosts = $this->postModel->countAll();
        $onlineCount= count($this->userModel->getOnlineUsers());
        require 'views/admin/dashboard.php';
    }

    public function deleteUser() {
        $id = (int)($_GET['id'] ?? 0);
        $me = (int)$_SESSION['user_id'];

        if ($id === $me) {
            setFlash('danger', 'You cannot delete your own account.');
        } elseif ($id) {
            $this->userModel->delete($id);
            setFlash('success', 'User deleted successfully.');
        }

        header('Location: ' . URLROOT . '/index.php?page=admin'); exit;
    }
}   