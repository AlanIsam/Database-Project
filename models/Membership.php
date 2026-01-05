<?php
require_once __DIR__ . '/../config/database.php';

class Membership {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM membership_type");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM membership_type WHERE type_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO membership_type (type_name, monthly_fee) VALUES (?, ?)");
        return $stmt->execute([$data['type_name'], $data['monthly_fee']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE membership_type SET type_name = ?, monthly_fee = ? WHERE type_id = ?");
        return $stmt->execute([$data['type_name'], $data['monthly_fee'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM membership_type WHERE type_id = ?");
        return $stmt->execute([$id]);
    }
}
?>