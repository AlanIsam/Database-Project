<?php
require_once __DIR__ . '/../config/database.php';

class TrainerCertification {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT tc.*, e.employee_name, c.cert_name
            FROM trainer_certification tc
            JOIN employee e ON tc.employee_id = e.employee_id
            JOIN certification c ON tc.cert_id = c.cert_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByTrainer($employee_id) {
        $stmt = $this->pdo->prepare("
            SELECT tc.*, c.cert_name
            FROM trainer_certification tc
            JOIN certification c ON tc.cert_id = c.cert_id
            WHERE tc.employee_id = ?
        ");
        $stmt->execute([$employee_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO trainer_certification (employee_id, cert_id) VALUES (?, ?)");
        return $stmt->execute([$data['employee_id'], $data['cert_id']]);
    }

    public function delete($employee_id, $cert_id) {
        $stmt = $this->pdo->prepare("DELETE FROM trainer_certification WHERE employee_id = ? AND cert_id = ?");
        return $stmt->execute([$employee_id, $cert_id]);
    }
}
?>