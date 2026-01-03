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
            SELECT c.*, p.program_name, e.employee_name as trainer_name, pc.category_name
            FROM class c
            JOIN program p ON c.program_id = p.program_id
            JOIN employee e ON c.employee_id = e.employee_id
            LEFT JOIN program_category pc ON p.category_id = pc.category_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, p.program_name, e.employee_name as trainer_name, pc.category_name
            FROM class c
            JOIN program p ON c.program_id = p.program_id
            JOIN employee e ON c.employee_id = e.employee_id
            LEFT JOIN program_category pc ON p.category_id = pc.category_id
            WHERE c.class_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO class (class_date, start_time, end_time, room_number, program_id, employee_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['class_date'], $data['start_time'], $data['end_time'], $data['room_number'], $data['program_id'], $data['employee_id']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE class SET class_date = ?, start_time = ?, end_time = ?, room_number = ?, program_id = ?, employee_id = ? WHERE class_id = ?");
        return $stmt->execute([$data['class_date'], $data['start_time'], $data['end_time'], $data['room_number'], $data['program_id'], $data['employee_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM class WHERE class_id = ?");
        return $stmt->execute([$id]);
    }
}
?>