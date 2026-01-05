<?php
require_once __DIR__ . '/../config/database.php';

class ProgramCategory {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM program_category");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM program_category WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO program_category (category_name) VALUES (?)");
        return $stmt->execute([$data['category_name']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE program_category SET category_name = ? WHERE category_id = ?");
        return $stmt->execute([$data['category_name'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM program_category WHERE category_id = ?");
        return $stmt->execute([$id]);
    }
}
?>