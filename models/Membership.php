<?php
require_once __DIR__ . '/../config/database.php';

class Membership {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT ms.*, m.first_name, m.last_name
            FROM memberships ms
            JOIN members m ON ms.member_id = m.member_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT ms.*, m.first_name, m.last_name
            FROM memberships ms
            JOIN members m ON ms.member_id = m.member_id
            WHERE ms.membership_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO memberships (member_id, membership_type, start_date, end_date, fee) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['member_id'], $data['membership_type'], $data['start_date'], $data['end_date'], $data['fee']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE memberships SET member_id = ?, membership_type = ?, start_date = ?, end_date = ?, fee = ? WHERE membership_id = ?");
        return $stmt->execute([$data['member_id'], $data['membership_type'], $data['start_date'], $data['end_date'], $data['fee'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM memberships WHERE membership_id = ?");
        return $stmt->execute([$id]);
    }
}
?>