<?php
require_once __DIR__ . '/../config/database.php';

class ClassModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT c.*, p.name as program_name, t.first_name, t.last_name, pc.name as category_name
            FROM classes c
            JOIN programs p ON c.program_id = p.program_id
            JOIN trainers t ON c.trainer_id = t.trainer_id
            JOIN program_categories pc ON p.category_id = pc.category_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.name as program_name, t.first_name, t.last_name, pc.name as category_name
            FROM classes c
            JOIN programs p ON c.program_id = p.program_id
            JOIN trainers t ON c.trainer_id = t.trainer_id
            JOIN program_categories pc ON p.category_id = pc.category_id
            WHERE c.class_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO classes (program_id, trainer_id, scheduled_date, scheduled_time, status, capacity) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['program_id'], $data['trainer_id'], $data['scheduled_date'], $data['scheduled_time'], $data['status'], $data['capacity']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE classes SET program_id = ?, trainer_id = ?, scheduled_date = ?, scheduled_time = ?, status = ?, capacity = ? WHERE class_id = ?");
        return $stmt->execute([$data['program_id'], $data['trainer_id'], $data['scheduled_date'], $data['scheduled_time'], $data['status'], $data['capacity'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM classes WHERE class_id = ?");
        return $stmt->execute([$id]);
    }
}
?>