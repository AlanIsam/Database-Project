<?php
require_once __DIR__ . '/../config/database.php';

class Program {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT p.*, pc.name as category_name FROM programs p JOIN program_categories pc ON p.category_id = pc.category_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, pc.name as category_name FROM programs p JOIN program_categories pc ON p.category_id = pc.category_id WHERE p.program_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO programs (name, category_id, description, duration_weeks, fee) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['category_id'], $data['description'], $data['duration_weeks'], $data['fee']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE programs SET name = ?, category_id = ?, description = ?, duration_weeks = ?, fee = ? WHERE program_id = ?");
        return $stmt->execute([$data['name'], $data['category_id'], $data['description'], $data['duration_weeks'], $data['fee'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM programs WHERE program_id = ?");
        return $stmt->execute([$id]);
    }

    // Top 5 popular programs
    public function getTopPrograms() {
        $stmt = $this->pdo->query("
            SELECT p.program_id, p.name, pc.name as category_name, t.first_name, t.last_name,
                   COUNT(e.enrollment_id) as enrolled_members
            FROM programs p
            JOIN program_categories pc ON p.category_id = pc.category_id
            LEFT JOIN classes c ON p.program_id = c.program_id
            LEFT JOIN enrollments e ON c.class_id = e.class_id
            LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
            GROUP BY p.program_id, p.name, pc.name, t.first_name, t.last_name
            ORDER BY enrolled_members DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>