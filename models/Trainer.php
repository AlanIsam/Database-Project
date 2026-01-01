<?php
require_once __DIR__ . '/../config/database.php';

class Trainer {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM trainers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM trainers WHERE trainer_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO trainers (first_name, last_name, email, phone, hire_date) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['hire_date']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE trainers SET first_name = ?, last_name = ?, email = ?, phone = ?, hire_date = ? WHERE trainer_id = ?");
        return $stmt->execute([$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['hire_date'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM trainers WHERE trainer_id = ?");
        return $stmt->execute([$id]);
    }

    // Trainer performance report
    public function getPerformanceReport() {
        $stmt = $this->pdo->query("
            SELECT t.trainer_id, t.first_name, t.last_name,
                   COUNT(c.class_id) as total_classes,
                   SUM(CASE WHEN c.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_classes
            FROM trainers t
            LEFT JOIN classes c ON t.trainer_id = c.trainer_id
            GROUP BY t.trainer_id, t.first_name, t.last_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>