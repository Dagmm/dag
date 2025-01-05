<?php
require_once 'db.php';

class Portfolio {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function getAboutSection() {
        $sql = "SELECT * FROM about LIMIT 1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getProjects() {
        $sql = "SELECT * FROM projects";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>

