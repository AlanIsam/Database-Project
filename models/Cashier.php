<?php
require_once __DIR__ . '/../config/database.php';

class Cashier {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT c.*, e.employee_name, e.employee_ic, e.employee_contact, e.gender, e.date_of_birth, e.date_working, e.employee_salary FROM cashier c JOIN employee e ON c.employee_id = e.employee_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT c.*, e.employee_name, e.employee_ic, e.employee_contact, e.gender, e.date_of_birth, e.date_working, e.employee_salary FROM cashier c JOIN employee e ON c.employee_id = e.employee_id WHERE c.employee_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // First create employee
        $stmt = $this->pdo->prepare("INSERT INTO employee (employee_name, employee_ic, employee_contact, gender, date_of_birth, date_working, employee_salary, employee_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'C')");
        $stmt->execute([$data['employee_name'], $data['employee_ic'], $data['employee_contact'], $data['gender'], $data['date_of_birth'], $data['date_working'], $data['employee_salary']]);
        $employee_id = $this->pdo->lastInsertId();
        
        // Then create cashier
        $stmt = $this->pdo->prepare("INSERT INTO cashier (employee_id, work_shift) VALUES (?, ?)");
        return $stmt->execute([$employee_id, $data['work_shift']]);
    }

    public function update($id, $data) {
        // Update employee
        $stmt = $this->pdo->prepare("UPDATE employee SET employee_name = ?, employee_ic = ?, employee_contact = ?, gender = ?, date_of_birth = ?, date_working = ?, employee_salary = ? WHERE employee_id = ?");
        $stmt->execute([$data['employee_name'], $data['employee_ic'], $data['employee_contact'], $data['gender'], $data['date_of_birth'], $data['date_working'], $data['employee_salary'], $id]);
        
        // Update cashier
        $stmt = $this->pdo->prepare("UPDATE cashier SET work_shift = ? WHERE employee_id = ?");
        return $stmt->execute([$data['work_shift'], $id]);
    }

    public function delete($id) {
        // Delete cashier first
        $stmt = $this->pdo->prepare("DELETE FROM cashier WHERE employee_id = ?");
        $stmt->execute([$id]);
        
        // Then delete employee
        $stmt = $this->pdo->prepare("DELETE FROM employee WHERE employee_id = ?");
        return $stmt->execute([$id]);
    }
}
?>