<?php
class Post {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll($categoryId=null, $limit=15, $offset=0) {
        $where = $categoryId ? "WHERE p.category_id=" . (int)$categoryId : "";
        $stmt = $this->db->prepare(
            "SELECT p.*, u.username, u.role as user_role, c.name as category_name,
             (SELECT COUNT(*) FROM replies r WHERE r.post_id=p.id) as reply_count
             FROM posts p
             JOIN users u ON p.user_id=u.id
             JOIN categories c ON p.category_id=c.id
             $where
             ORDER BY p.is_pinned DESC, p.created_at DESC
             LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('ii', $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAll($categoryId=null) {
        $where = $categoryId ? "WHERE category_id=" . (int)$categoryId : "";
        return $this->db->query("SELECT COUNT(*) as c FROM posts $where")->fetch_assoc()['c'];
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT p.*, u.username, u.role as user_role, c.name as category_name
             FROM posts p JOIN users u ON p.user_id=u.id
             JOIN categories c ON p.category_id=c.id
             WHERE p.id=? LIMIT 1"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($userId, $categoryId, $title, $content) {
        $stmt = $this->db->prepare(
            "INSERT INTO posts (user_id,category_id,title,content) VALUES (?,?,?,?)"
        );
        $stmt->bind_param('iiss', $userId, $categoryId, $title, $content);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function togglePin($id) {
    $stmt = $this->db->prepare("UPDATE posts SET is_pinned = NOT is_pinned WHERE id=?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
    }

    public function incrementViews($id) {
        $this->db->query("UPDATE posts SET views=views+1 WHERE id=" . (int)$id);
    }

    public function getReplies($postId) {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.username, u.role as user_role
             FROM replies r JOIN users u ON r.user_id=u.id
             WHERE r.post_id=? ORDER BY r.created_at ASC"
        );
        $stmt->bind_param('i', $postId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addReply($postId, $userId, $content) {
        $stmt = $this->db->prepare(
            "INSERT INTO replies (post_id,user_id,content) VALUES (?,?,?)"
        );
        $stmt->bind_param('iis', $postId, $userId, $content);
        return $stmt->execute();
    }

    public function deleteReply($id) {
        $stmt = $this->db->prepare("DELETE FROM replies WHERE id=?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getRecentPosts($limit=5) {
        $stmt = $this->db->prepare(
            "SELECT p.id, p.title, u.username, p.created_at
             FROM posts p JOIN users u ON p.user_id=u.id
             ORDER BY p.created_at DESC LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}