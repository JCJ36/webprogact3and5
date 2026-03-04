<?php
class ForumController {
    private $postModel;
    private $categoryModel;
    private $userModel;

    public function __construct() {
        require_once 'models/Post.php';
        require_once 'models/Category.php';
        require_once 'models/User.php';
        $this->postModel     = new Post();
        $this->categoryModel = new Category();
        $this->userModel     = new User();
    }

    public function index() {
        $categoryId   = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
        $page         = max(1, (int)($_GET['p'] ?? 1));
        $perPage      = POSTS_PER_PAGE;
        $total        = $this->postModel->countAll($categoryId);
        $offset       = ($page - 1) * $perPage;
        $posts        = $this->postModel->getAll($categoryId, $perPage, $offset);
        $categories   = $this->categoryModel->getAll();
        $onlineUsers  = $this->userModel->getOnlineUsers();
        $totalPages   = max(1, (int)ceil($total / $perPage));
        $recentPosts  = $this->postModel->getRecentPosts(5);
        require 'views/forum/index.php';
    }

    public function viewPost() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { header('Location: ' . URLROOT . '/index.php?page=forum'); exit; }

        $post        = $this->postModel->findById($id);
        if (!$post)  { setFlash('danger','Post not found.'); header('Location: ' . URLROOT . '/index.php?page=forum'); exit; }

        $this->postModel->incrementViews($id);
        $replies      = $this->postModel->getReplies($id);
        $onlineUsers  = $this->userModel->getOnlineUsers();
        require 'views/forum/post.php';
    }

    public function createPost() {
        $categories = $this->categoryModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title      = trim($_POST['title'] ?? '');
            $content    = trim($_POST['content'] ?? '');
            $categoryId = (int)($_POST['category_id'] ?? 0);

            if (empty($title) || empty($content) || !$categoryId) {
                setFlash('danger', 'Please fill in all fields.');
                require 'views/forum/create.php'; return;
            }

            $postId = $this->postModel->create($_SESSION['user_id'], $categoryId, $title, $content);
            setFlash('success', 'Post created successfully!');
            header('Location: ' . URLROOT . '/index.php?page=forum&action=post&id=' . $postId); exit;
        }

        require 'views/forum/create.php';
    }

    public function deletePost() {
        if (!hasRole('mod', 'admin')) {
            setFlash('danger', 'Permission denied.');
            header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id) $this->postModel->delete($id);

        setFlash('success', 'Post deleted.');
        header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
    }

    public function pinPost() {
        if (!hasRole('mod', 'admin')) {
            setFlash('danger', 'Permission denied.');
            header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->findById($id);

        if (!$post) {
            setFlash('danger', 'Post not found.');
            header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
        }

        $wasPinned = (bool)$post['is_pinned'];

        $this->postModel->togglePin($id);  
        $msg = $wasPinned ? 'Post unpinned' : 'Post pinned';
        setFlash('success', $msg);
        header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
    }

    public function addReply() {
        $postId  = (int)($_POST['post_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if (!$postId || empty($content)) {
            setFlash('danger', 'Reply cannot be empty.');
        } else {
            $this->postModel->addReply($postId, $_SESSION['user_id'], $content);
            setFlash('success', 'Reply posted!');
        }

        header('Location: ' . URLROOT . '/index.php?page=forum&action=post&id=' . $postId . '#replies'); exit;
    }

    public function deleteReply() {
        if (!hasRole('mod', 'admin')) {
            setFlash('danger', 'Permission denied.');
            header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
        }

        $id     = (int)($_GET['id'] ?? 0);
        $postId = (int)($_GET['post_id'] ?? 0);
        if ($id) $this->postModel->deleteReply($id);

        setFlash('success', 'Reply deleted.');
        header('Location: ' . URLROOT . '/index.php?page=forum&action=post&id=' . $postId . '#replies'); exit;
    }
}