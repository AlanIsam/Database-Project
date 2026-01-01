<?php
require_once __DIR__ . '/../config/database.php';

class TrainerProgramHistory {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT tph.*, t.first_name, t.last_name, pc.name as category_name
            FROM trainer_program_history tph
            JOIN trainers t ON tph.trainer_id = t.trainer_id
            JOIN program_categories pc ON tph.category_id = pc.category_id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT tph.*, t.first_name, t.last_name, pc.name as category_name
            FROM trainer_program_history tph
            JOIN trainers t ON tph.trainer_id = t.trainer_id
            JOIN program_categories pc ON tph.category_id = pc.category_id
            WHERE tph.history_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO trainer_program_history (trainer_id, category_id, assigned_date, end_date) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['trainer_id'], $data['category_id'], $data['assigned_date'], $data['end_date']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE trainer_program_history SET trainer_id = ?, category_id = ?, assigned_date = ?, end_date = ? WHERE history_id = ?");
        return $stmt->execute([$data['trainer_id'], $data['category_id'], $data['assigned_date'], $data['end_date'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM trainer_program_history WHERE history_id = ?");
        return $stmt->execute([$id]);
    }
}
?>