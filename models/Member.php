<?php
require_once __DIR__ . '/../config/database.php';

class Member {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM members");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM members WHERE member_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO members (first_name, last_name, email, phone, join_date, membership_status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['join_date'], $data['membership_status']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE members SET first_name = ?, last_name = ?, email = ?, phone = ?, join_date = ?, membership_status = ? WHERE member_id = ?");
        return $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['join_date'], $data['membership_status'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM members WHERE member_id = ?");
        return $stmt->execute([$id]);
    }

    // Additional method for member stats
    public function getMemberStats() {
        $stmt = $this->pdo->query("
            SELECT m.member_id, m.first_name, m.last_name, m.membership_status,
                   COUNT(DISTINCT e.class_id) as programs_enrolled,
                   COUNT(DISTINCT CASE WHEN e.status = 'completed' THEN e.class_id END) as classes_attended,
                   SUM(p.amount) as total_payments
            FROM members m
            LEFT JOIN enrollments e ON m.member_id = e.member_id
            LEFT JOIN payments p ON m.member_id = p.member_id
            GROUP BY m.member_id, m.first_name, m.last_name, m.membership_status
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>