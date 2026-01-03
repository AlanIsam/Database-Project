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
            SELECT p.*, m.member_name, e.employee_name as cashier_name
            FROM payment p
            JOIN member m ON p.member_id = m.member_id
            JOIN employee e ON p.employee_id = e.employee_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, m.member_name, e.employee_name as cashier_name
            FROM payment p
            JOIN member m ON p.member_id = m.member_id
            JOIN employee e ON p.employee_id = e.employee_id
            WHERE p.payment_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO payment (payment_date, payment_amount, payment_method, member_id, employee_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['payment_date'], $data['payment_amount'], $data['payment_method'], $data['member_id'], $data['employee_id']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE payment SET payment_date = ?, payment_amount = ?, payment_method = ?, member_id = ?, employee_id = ? WHERE payment_id = ?");
        return $stmt->execute([$data['payment_date'], $data['payment_amount'], $data['payment_method'], $data['member_id'], $data['employee_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM payment WHERE payment_id = ?");
        return $stmt->execute([$id]);
    }

    // Quarterly and annual membership fees
    public function getMembershipFees($period = 'annual') {
        if ($period == 'quarterly') {
            $group = "YEAR(payment_date), QUARTER(payment_date)";
        } else {
            $group = "YEAR(payment_date)";
        }
        $stmt = $this->pdo->query("
            SELECT {$group} as period, SUM(payment_amount) as total_fees
            FROM payment
            GROUP BY {$group}
            ORDER BY period
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>