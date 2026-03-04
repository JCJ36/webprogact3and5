<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'forum_db');

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die('DB Error: ' . $this->connection->connect_error);
        }
        $this->connection->set_charset('utf8mb4');
    }

    public static function getInstance() {
        if (self::$instance === null) self::$instance = new Database();
        return self::$instance;
    }

    public function getConnection() { return $this->connection; }
    public function query($sql) { return $this->connection->query($sql); }
    public function prepare($sql) { return $this->connection->prepare($sql); }
    public function escape($v) { return $this->connection->real_escape_string($v); }
    public function lastInsertId() { return $this->connection->insert_id; }
    public function affectedRows() { return $this->connection->affected_rows; }
}