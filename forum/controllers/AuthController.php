<?php
class AuthController {
    private $userModel;

    public function __construct() {
        require_once 'models/User.php';
        $this->userModel = new User();
    }

    public function landing() {
        require 'views/auth/landing.php';
    }

    public function showLogin() {
        require 'views/auth/login.php';
    }

    public function showRegister() {
        require 'views/auth/register.php';
    }

    public function login() {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            setFlash('danger', 'Please fill in all fields.');
            header('Location: ' . URLROOT . '/index.php?page=login'); exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            setFlash('danger', 'Invalid email or password.');
            header('Location: ' . URLROOT . '/index.php?page=login'); exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user']    = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
        ];

        setFlash('success', 'Welcome back, ' . $user['username'] . '!');
        header('Location: ' . URLROOT . '/index.php?page=forum'); exit;
    }

    public function register() {
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['confirm'] ?? '';

        $errors = [];
        if (strlen($username) < 3 || strlen($username) > 50)
            $errors[] = 'Username must be 3-50 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Invalid email address.';
        if (strlen($password) < 6)
            $errors[] = 'Password must be at least 6 characters.';
        if ($password !== $confirm)
            $errors[] = 'Passwords do not match.';
        if ($this->userModel->findByUsername($username))
            $errors[] = 'Username is already taken.';
        if ($this->userModel->findByEmail($email))
            $errors[] = 'Email is already registered.';

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            header('Location: ' . URLROOT . '/index.php?page=register'); exit;
        }

        $this->userModel->create($username, $email, $password);
        setFlash('success', 'Account created! You can now log in.');
        header('Location: ' . URLROOT . '/index.php?page=login'); exit;
    }

    public function logout() {
        if (isLoggedIn()) {
            $db = Database::getInstance();
            $uid = (int)$_SESSION['user_id'];
            $db->query("DELETE FROM user_sessions WHERE user_id=$uid");
            $db->query("UPDATE users SET is_online=0 WHERE id=$uid");
        }
        session_destroy();
        header('Location: ' . URLROOT . '/index.php?page=landing'); exit;
    }
}