<?php
require_once __DIR__ . '/../config/database.php';

class Certification {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM certification");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM certification WHERE cert_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO certification (cert_name) VALUES (?)");
        return $stmt->execute([$data['cert_name']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE certification SET cert_name = ? WHERE cert_id = ?");
        return $stmt->execute([$data['cert_name'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM certification WHERE cert_id = ?");
        return $stmt->execute([$id]);
    }
}
?>