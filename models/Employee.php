<?php
require_once __DIR__ . '/../config/database.php';

class Employee {
    public $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM employee");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM employee WHERE employee_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO employee (employee_name, employee_ic, employee_contact, gender, date_of_birth, date_working, employee_salary, employee_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $ok = $stmt->execute([
            $data['employee_name'],
            $data['employee_ic'],
            $data['employee_contact'],
            $data['gender'],
            $data['date_of_birth'],
            $data['date_working'],
            $data['employee_salary'],
            $data['employee_type']
        ]);
        return $ok ? $this->pdo->lastInsertId() : false;
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE employee SET employee_name = ?, employee_ic = ?, employee_contact = ?, gender = ?, date_of_birth = ?, date_working = ?, employee_salary = ?, employee_type = ? WHERE employee_id = ?");
        return $stmt->execute([
            $data['employee_name'],
            $data['employee_ic'],
            $data['employee_contact'],
            $data['gender'],
            $data['date_of_birth'],
            $data['date_working'],
            $data['employee_salary'],
            $data['employee_type'],
            $id
        ]);
    }

    public function delete($id) {
        try {
            $this->pdo->beginTransaction();
            $this->cascadeDeleteByPK('employee', 'employee_id', $id, []);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // Recursively delete all rows that reference the target row, then delete the target row itself.
    private function cascadeDeleteByPK($table, $pkColumn, $pkValue, array $visited) {
        $this->guardIdentifier($table);
        $this->guardIdentifier($pkColumn);

        // Avoid cycles
        $visitKey = $table . '|' . $pkColumn . '|' . $pkValue;
        if (in_array($visitKey, $visited, true)) {
            return;
        }
        $visited[] = $visitKey;

        // Find child tables referencing this table
        $refs = $this->getReferencingConstraints($table, $pkColumn);
        foreach ($refs as $ref) {
            $childTable = $ref['TABLE_NAME'];
            $childFK    = $ref['COLUMN_NAME'];
            $childPK    = $this->getPrimaryKeyColumn($childTable);
            if (!$childPK) {
                continue; // skip tables without a single-column PK
            }

            // Collect child PKs that reference this row
            $sql = "SELECT `{$childPK}` FROM `{$childTable}` WHERE `{$childFK}` = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$pkValue]);
            $childIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            // Recurse into children before deleting them
            foreach ($childIds as $childId) {
                $this->cascadeDeleteByPK($childTable, $childPK, $childId, $visited);
            }

            // Delete the children rows
            $delSql = "DELETE FROM `{$childTable}` WHERE `{$childFK}` = ?";
            $delStmt = $this->pdo->prepare($delSql);
            $delStmt->execute([$pkValue]);
        }

        // Delete the current row
        $deleteSql = "DELETE FROM `{$table}` WHERE `{$pkColumn}` = ?";
        $deleteStmt = $this->pdo->prepare($deleteSql);
        $deleteStmt->execute([$pkValue]);
    }

    private function getReferencingConstraints($table, $column) {
        $sql = "SELECT TABLE_NAME, COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME = :table
                  AND REFERENCED_COLUMN_NAME = :column";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['table' => $table, 'column' => $column]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPrimaryKeyColumn($table) {
        $sql = "SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = :table
                  AND CONSTRAINT_NAME = 'PRIMARY'
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['table' => $table]);
        return $stmt->fetchColumn();
    }

    private function guardIdentifier($value) {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $value)) {
            throw new PDOException('Invalid identifier: ' . $value);
        }
    }
}
