<?php
require_once __DIR__ . '/../config/database.php';

class ProgramMember {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT pm.*, m.member_name, p.program_name
            FROM program_member pm
            JOIN member m ON pm.member_id = m.member_id
            JOIN program p ON pm.program_id = p.program_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByMember($member_id) {
        $stmt = $this->pdo->prepare("
            SELECT pm.*, p.program_name
            FROM program_member pm
            JOIN program p ON pm.program_id = p.program_id
            WHERE pm.member_id = ?
        ");
        $stmt->execute([$member_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO program_member (program_id, member_id) VALUES (?, ?)");
        return $stmt->execute([$data['program_id'], $data['member_id']]);
    }

    public function delete($program_id, $member_id) {
        $stmt = $this->pdo->prepare("DELETE FROM program_member WHERE program_id = ? AND member_id = ?");
        return $stmt->execute([$program_id, $member_id]);
    }
}
?>