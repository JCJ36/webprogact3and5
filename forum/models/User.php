<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($username, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username,email,password) VALUES (?,?,?)");
        $stmt->bind_param('sss', $username, $email, $hash);
        return $stmt->execute();
    }

    public function getAll($limit=20, $offset=0) {
        $stmt = $this->db->prepare("SELECT id,username,email,role,is_online,created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=? AND role!='admin'");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getOnlineUsers() {
        $threshold = date('Y-m-d H:i:s', time() - ONLINE_THRESHOLD);
        $stmt = $this->db->prepare("SELECT u.id, u.username, u.role FROM users u
            JOIN user_sessions us ON u.id=us.user_id
            WHERE us.last_activity > ? ORDER BY u.username");
        $stmt->bind_param('s', $threshold);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOnlineStatus($userId, $online) {
        $stmt = $this->db->prepare("UPDATE users SET is_online=? WHERE id=?");
        $stmt->bind_param('ii', $online, $userId);
        $stmt->execute();
    }
}