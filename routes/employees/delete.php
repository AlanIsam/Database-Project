
<?php
// Always disable output buffering and display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../models/Employee.php';
$employee = new Employee();
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['delete_employee_id']) ? $_POST['delete_employee_id'] : null);
$data = $employee->getById($id);

function send_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function get_related_tables($pdo, $employeeId) {
    // Find tables referencing employee.employee_id and check which have rows for this employee
    $stmt = $pdo->prepare(
        "SELECT kcu.TABLE_NAME, kcu.COLUMN_NAME
         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
         WHERE kcu.REFERENCED_TABLE_SCHEMA = DATABASE()
           AND kcu.REFERENCED_TABLE_NAME = 'employee'
           AND kcu.REFERENCED_COLUMN_NAME = 'employee_id'"
    );
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $related = [];
    foreach ($refs as $ref) {
        $table = $ref['TABLE_NAME'];
        $col = $ref['COLUMN_NAME'];
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$col}` = ?";
        $countStmt = $pdo->prepare($sql);
        $countStmt->execute([$employeeId]);
        if ($countStmt->fetchColumn() > 0) {
            $related[] = $table;
        }
    }
    return $related;
}

// AJAX: fetch related tables for an employee id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'related') {
    $employeeId = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$employeeId) {
        send_json(['success' => false, 'error' => 'Missing employee id']);
    }
    $related = get_related_tables($employee->pdo, $employeeId);
    send_json(['success' => true, 'related' => $related]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_employee_id'])) {
        try {
            $deleteId = $_POST['delete_employee_id'];
            $result = $employee->delete($deleteId);
            if ($result) {
                send_json(['success' => true]);
            } else {
                $related = get_related_tables($employee->pdo, $deleteId);
                if (!empty($related)) {
                    $error = 'Failed to delete employee. This employee is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
                } else {
                    $error = 'Failed to delete employee due to unknown database constraints.';
                }
                send_json(['success' => false, 'error' => $error]);
            }
        } catch (Throwable $ex) {
            send_json(['success' => false, 'error' => 'Server error: ' . $ex->getMessage()]);
        }
    }

    // Fallback: normal POST (from direct page)
    if ($employee->delete($id)) {
        header('Location: view.php');
        exit;
    } else {
        $related = get_related_tables($employee->pdo, $id);
        if (!empty($related)) {
            $error = 'Failed to delete employee. This employee is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
        } else {
            $error = 'Failed to delete employee due to unknown database constraints.';
        }
    }
}

?>

<h2>Delete Employee</h2>
<p>Are you sure you want to delete <?php echo $data['employee_name']; ?>?</p>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>
