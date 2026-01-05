<?php
require_once __DIR__ . '/../config/database.php';

class Trainer {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT t.employee_id, e.employee_name, e.employee_type, t.expertise, t.certification FROM trainer t JOIN employee e ON t.employee_id = e.employee_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($employee_id) {
        $stmt = $this->pdo->prepare("SELECT t.employee_id, e.employee_name, e.employee_type, t.expertise, t.certification FROM trainer t JOIN employee e ON t.employee_id = e.employee_id WHERE t.employee_id = ?");
        $stmt->execute([$employee_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPerformanceReport() {
        $stmt = $this->pdo->query("
            SELECT e.employee_name, COUNT(c.class_id) AS total_classes
            FROM trainer t
            JOIN employee e ON t.employee_id = e.employee_id
            LEFT JOIN class c ON t.employee_id = c.employee_id
            GROUP BY e.employee_name
            ORDER BY total_classes DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
