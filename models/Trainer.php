<?php
require_once __DIR__ . '/../config/database.php';

class Trainer {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT t.*, e.employee_name, e.employee_ic, e.employee_contact, e.gender, e.date_of_birth, e.date_working, e.employee_salary FROM trainer t JOIN employee e ON t.employee_id = e.employee_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT t.*, e.employee_name, e.employee_ic, e.employee_contact, e.gender, e.date_of_birth, e.date_working, e.employee_salary FROM trainer t JOIN employee e ON t.employee_id = e.employee_id WHERE t.employee_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // First create employee
        $stmt = $this->pdo->prepare("INSERT INTO employee (employee_name, employee_ic, employee_contact, gender, date_of_birth, date_working, employee_salary, employee_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'T')");
        $stmt->execute([$data['employee_name'], $data['employee_ic'], $data['employee_contact'], $data['gender'], $data['date_of_birth'], $data['date_working'], $data['employee_salary']]);
        $employee_id = $this->pdo->lastInsertId();
        
        // Then create trainer
        $stmt = $this->pdo->prepare("INSERT INTO trainer (employee_id, expertise, certification) VALUES (?, ?, ?)");
        return $stmt->execute([$employee_id, $data['expertise'], $data['certification']]);
    }

    public function update($id, $data) {
        // Update employee
        $stmt = $this->pdo->prepare("UPDATE employee SET employee_name = ?, employee_ic = ?, employee_contact = ?, gender = ?, date_of_birth = ?, date_working = ?, employee_salary = ? WHERE employee_id = ?");
        $stmt->execute([$data['employee_name'], $data['employee_ic'], $data['employee_contact'], $data['gender'], $data['date_of_birth'], $data['date_working'], $data['employee_salary'], $id]);
        
        // Update trainer
        $stmt = $this->pdo->prepare("UPDATE trainer SET expertise = ?, certification = ? WHERE employee_id = ?");
        return $stmt->execute([$data['expertise'], $data['certification'], $id]);
    }

    public function delete($id) {
        // Delete trainer first
        $stmt = $this->pdo->prepare("DELETE FROM trainer WHERE employee_id = ?");
        $stmt->execute([$id]);
        
        // Then delete employee
        $stmt = $this->pdo->prepare("DELETE FROM employee WHERE employee_id = ?");
        return $stmt->execute([$id]);
    }

    // Trainer performance report
    public function getPerformanceReport() {
        $stmt = $this->pdo->query("
            SELECT t.employee_id, e.employee_name,
                   COUNT(c.class_id) as total_classes
            FROM trainer t
            JOIN employee e ON t.employee_id = e.employee_id
            LEFT JOIN class c ON t.employee_id = c.employee_id
            GROUP BY t.employee_id, e.employee_name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>