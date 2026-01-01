<?php
require_once __DIR__ . '/../config/database.php';

class ProgramCategory {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM program_categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM program_categories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO program_categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE program_categories SET name = ?, description = ? WHERE category_id = ?");
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM program_categories WHERE category_id = ?");
        return $stmt->execute([$id]);
    }
}
?>