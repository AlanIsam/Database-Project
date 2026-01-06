<?php
require_once __DIR__ . '/../config/database.php';

class ClassModel {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        date_default_timezone_set('Asia/Kuala_Lumpur');
    }

    public function getAll() {
        $this->autoArchiveClasses();

        $stmt = $this->pdo->query("
            SELECT c.*, p.program_name, e.employee_name as trainer_name, pc.category_name
            FROM class c
            JOIN program p ON c.program_id = p.program_id
            JOIN employee e ON c.employee_id = e.employee_id
            LEFT JOIN program_category pc ON p.category_id = pc.category_id
            ORDER BY c.class_date DESC, c.start_time ASC
        ");
        
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($classes as &$class) {
            $class['dynamic_status'] = $this->calculateStatus($class);
        }

        return $classes;
    }

    public function getById($id) {
        $this->autoArchiveClasses();

        $stmt = $this->pdo->prepare("
            SELECT c.*, p.program_name, e.employee_name as trainer_name, pc.category_name
            FROM class c
            JOIN program p ON c.program_id = p.program_id
            JOIN employee e ON c.employee_id = e.employee_id
            LEFT JOIN program_category pc ON p.category_id = pc.category_id
            WHERE c.class_id = ?
        ");
        $stmt->execute([$id]);
        $class = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($class) {
            $class['dynamic_status'] = $this->calculateStatus($class);
        }
        return $class;
    }

    private function autoArchiveClasses() {
        $currentDateTime = date('Y-m-d H:i:s');

        $sql = "
            UPDATE class 
            SET class_status = 'Completed' 
            WHERE class_status = 'Active' 
            AND TIMESTAMP(DATE(class_date), end_time) < ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$currentDateTime]);
    }

    private function calculateStatus($class) {
        if (isset($class['class_status']) && ($class['class_status'] === 'Cancelled' || $class['class_status'] === 'Completed')) {
            return $class['class_status'];
        }

        $datePart = substr($class['class_date'], 0, 10); 
        $classEndTime = strtotime("$datePart " . $class['end_time']);
        $currentTime = time();

        if ($classEndTime < $currentTime) {
            return 'Completed';
        } else {
            return 'Active';
        }
    }

    public function create($data) {
        if (!$this->trainerExists($data['employee_id'])) return false;
        
        $status = isset($data['class_status']) ? $data['class_status'] : 'Active';
        
        $stmt = $this->pdo->prepare("INSERT INTO class (class_date, start_time, end_time, room_number, program_id, employee_id, class_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['class_date'], $data['start_time'], $data['end_time'], $data['room_number'], $data['program_id'], $data['employee_id'], $status]);
    }

    public function update($id, $data) {
        if (!$this->trainerExists($data['employee_id'])) return false;

        $stmt = $this->pdo->prepare("UPDATE class SET class_date = ?, start_time = ?, end_time = ?, room_number = ?, program_id = ?, employee_id = ?, class_status = ? WHERE class_id = ?");
        return $stmt->execute([$data['class_date'], $data['start_time'], $data['end_time'], $data['room_number'], $data['program_id'], $data['employee_id'], $data['class_status'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM class WHERE class_id = ?");
        return $stmt->execute([$id]);
    }

    private function trainerExists($employeeId) {
        $stmt = $this->pdo->prepare("SELECT 1 FROM trainer WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        return (bool) $stmt->fetchColumn();
    }
}
?>
