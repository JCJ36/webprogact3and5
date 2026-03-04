<?php
class Category {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll() {
        return $this->db->query(
            "SELECT c.*, COUNT(p.id) as post_count
             FROM categories c LEFT JOIN posts p ON c.id=p.category_id
             GROUP BY c.id ORDER BY c.sort_order"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id=? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}