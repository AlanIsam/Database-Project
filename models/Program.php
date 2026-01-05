<?php
require_once __DIR__ . '/../config/database.php';

class Program {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT p.*, pc.category_name, e.employee_name as trainer_name FROM program p LEFT JOIN program_category pc ON p.category_id = pc.category_id LEFT JOIN employee e ON p.employee_id = e.employee_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, pc.category_name, e.employee_name as trainer_name FROM program p LEFT JOIN program_category pc ON p.category_id = pc.category_id LEFT JOIN employee e ON p.employee_id = e.employee_id WHERE p.program_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (!$this->trainerExists($data['employee_id'])) {
            return false; // avoid FK violation when selecting non-trainer
        }
        $stmt = $this->pdo->prepare("INSERT INTO program (program_name, program_duration, program_fee, employee_id, category_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['program_name'], $data['program_duration'], $data['program_fee'], $data['employee_id'], $data['category_id']]);
    }

    public function update($id, $data) {
        if (!$this->trainerExists($data['employee_id'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE program SET program_name = ?, program_duration = ?, program_fee = ?, employee_id = ?, category_id = ? WHERE program_id = ?");
        return $stmt->execute([$data['program_name'], $data['program_duration'], $data['program_fee'], $data['employee_id'], $data['category_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM program WHERE program_id = ?");
        return $stmt->execute([$id]);
    }

    // Top 5 popular programs
    public function getTopPrograms() {
        $stmt = $this->pdo->query("
            SELECT p.program_id, p.program_name, pc.category_name, e.employee_name as trainer_name,
                   COUNT(pm.member_id) as enrolled_members
            FROM program p
            LEFT JOIN program_category pc ON p.category_id = pc.category_id
            LEFT JOIN employee e ON p.employee_id = e.employee_id
            LEFT JOIN program_member pm ON p.program_id = pm.program_id
            GROUP BY p.program_id, p.program_name, pc.category_name, e.employee_name
            ORDER BY enrolled_members DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function trainerExists($employeeId) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM trainer WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        return (bool) $stmt->fetchColumn();
    }
}
?>