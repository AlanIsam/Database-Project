<?php
require_once __DIR__ . '/../config/database.php';

class Member {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT m.*, mt.type_name, mt.monthly_fee FROM member m LEFT JOIN membership_type mt ON m.type_id = mt.type_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT m.*, mt.type_name, mt.monthly_fee FROM member m LEFT JOIN membership_type mt ON m.type_id = mt.type_id WHERE m.member_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO member (member_name, member_ic, member_contact, gender, date_of_birth, membership_status, type_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['member_name'], $data['member_ic'], $data['member_contact'], $data['gender'], $data['date_of_birth'], $data['membership_status'], $data['type_id']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE member SET member_name = ?, member_ic = ?, member_contact = ?, gender = ?, date_of_birth = ?, membership_status = ?, type_id = ? WHERE member_id = ?");
        return $stmt->execute([$data['member_name'], $data['member_ic'], $data['member_contact'], $data['gender'], $data['date_of_birth'], $data['membership_status'], $data['type_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM member WHERE member_id = ?");
        return $stmt->execute([$id]);
    }

    // Additional method for member stats
    public function getMemberStats() {
        $stmt = $this->pdo->query("
            SELECT m.member_id, m.member_name, m.membership_status,
                   COUNT(DISTINCT cm.class_id) as classes_enrolled,
                   COUNT(DISTINCT pm.program_id) as programs_enrolled,
                   SUM(p.payment_amount) as total_payments
            FROM member m
            LEFT JOIN class_member cm ON m.member_id = cm.member_id
            LEFT JOIN program_member pm ON m.member_id = pm.member_id
            LEFT JOIN payment p ON m.member_id = p.member_id
            GROUP BY m.member_id, m.member_name, m.membership_status
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>