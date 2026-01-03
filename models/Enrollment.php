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
            SELECT cm.*, m.member_name, c.class_date, c.start_time, c.end_time, p.program_name
            FROM class_member cm
            JOIN member m ON cm.member_id = m.member_id
            JOIN class c ON cm.class_id = c.class_id
            JOIN program p ON c.program_id = p.program_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMember($member_id) {
        $stmt = $this->pdo->prepare("
            SELECT cm.*, c.class_date, c.start_time, c.end_time, p.program_name
            FROM class_member cm
            JOIN class c ON cm.class_id = c.class_id
            JOIN program p ON c.program_id = p.program_id
            WHERE cm.member_id = ?
        ");
        $stmt->execute([$member_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO class_member (class_id, member_id) VALUES (?, ?)");
        return $stmt->execute([$data['class_id'], $data['member_id']]);
    }

    public function delete($class_id, $member_id) {
        $stmt = $this->pdo->prepare("DELETE FROM class_member WHERE class_id = ? AND member_id = ?");
        return $stmt->execute([$class_id, $member_id]);
    }
}
?>