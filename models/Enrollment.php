<?php
require_once __DIR__ . '/../config/database.php';

class Enrollment {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT e.*, m.first_name, m.last_name, c.scheduled_date, c.scheduled_time, p.name as program_name
            FROM enrollments e
            JOIN members m ON e.member_id = m.member_id
            JOIN classes c ON e.class_id = c.class_id
            JOIN programs p ON c.program_id = p.program_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, m.first_name, m.last_name, c.scheduled_date, c.scheduled_time, p.name as program_name
            FROM enrollments e
            JOIN members m ON e.member_id = m.member_id
            JOIN classes c ON e.class_id = c.class_id
            JOIN programs p ON c.program_id = p.program_id
            WHERE e.enrollment_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO enrollments (member_id, class_id, enrollment_date, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['member_id'], $data['class_id'], $data['enrollment_date'], $data['status']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE enrollments SET member_id = ?, class_id = ?, enrollment_date = ?, status = ? WHERE enrollment_id = ?");
        return $stmt->execute([$data['member_id'], $data['class_id'], $data['enrollment_date'], $data['status'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
        return $stmt->execute([$id]);
    }
}
?>