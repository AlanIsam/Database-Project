<?php
require_once __DIR__ . '/../config/database.php';

class Payment {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT p.*, m.first_name, m.last_name
            FROM payments p
            JOIN members m ON p.member_id = m.member_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, m.first_name, m.last_name
            FROM payments p
            JOIN members m ON p.member_id = m.member_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO payments (member_id, amount, payment_date, payment_type, description) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['member_id'], $data['amount'], $data['payment_date'], $data['payment_type'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE payments SET member_id = ?, amount = ?, payment_date = ?, payment_type = ?, description = ? WHERE payment_id = ?");
        return $stmt->execute([$data['member_id'], $data['amount'], $data['payment_date'], $data['payment_type'], $data['description'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE payment_id = ?");
        return $stmt->execute([$id]);
    }

    // Quarterly and annual membership fees
    public function getMembershipFees($period = 'annual') {
        if ($period == 'quarterly') {
            $group = "YEAR(payment_date), QUARTER(payment_date)";
        } else {
            $group = "YEAR(payment_date)";
        }
        $stmt = $this->pdo->prepare("
            SELECT $group, SUM(amount) as total_fees
            FROM payments
            WHERE payment_type = 'membership'
            GROUP BY $group
            ORDER BY $group DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>