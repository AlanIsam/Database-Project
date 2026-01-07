<?php
require_once '../../models/Employee.php';
$employeeError = null;
$employee = new Employee();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'employee_name' => $_POST['employee_name'],
        'employee_ic' => $_POST['employee_ic'],
        'employee_contact' => $_POST['employee_contact'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'date_working' => $_POST['date_working'],
        'employee_salary' => $_POST['employee_salary'],
        'employee_type' => $_POST['employee_type']
    ];
    try {
        $employee->pdo->beginTransaction();
        $employeeId = $employee->create($data);
        if (!$employeeId) {
            throw new Exception('Failed to create employee.');
        }
        // If cashier, also insert into cashier table
        if ($data['employee_type'] === 'C') {
            $workShift = isset($_POST['work_shift']) ? $_POST['work_shift'] : null;
            if (!$workShift) {
                throw new Exception('Work shift is required for cashier.');
            }
            $stmt = $employee->pdo->prepare('INSERT INTO cashier (employee_id, work_shift) VALUES (?, ?)');
            if (!$stmt->execute([$employeeId, $workShift])) {
                throw new Exception('Failed to create cashier record.');
            }
        } elseif ($data['employee_type'] === 'T') {
            // Insert trainer data
            $expertise = isset($_POST['expertise']) ? $_POST['expertise'] : null;
            $certification = isset($_POST['certification']) ? $_POST['certification'] : null;
            $stmt = $employee->pdo->prepare('INSERT INTO trainer (employee_id, expertise, certification) VALUES (?, ?, ?)');
            if (!$stmt->execute([$employeeId, $expertise, $certification])) {
                throw new Exception('Failed to create trainer record.');
            }
        }
        $employee->pdo->commit();
        header('Location: view.php?success=1');
        exit;
    } catch (Throwable $ex) {
        $employee->pdo->rollBack();
        $error = $ex->getMessage();
    }
}
include '../../views/header.php';
?>
<h2>Add New Employee</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="employee_name" class="form-label">Name</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
    </div>
    <div class="mb-3">
        <label for="employee_ic" class="form-label">IC Number (Without "-")</label>
        <input type="text" inputmode="numeric" pattern="\d{12}" maxlength="12" class="form-control" id="employee_ic" name="employee_ic" required data-bs-toggle="popover" data-bs-trigger="manual" data-bs-content="must be a 12 number IC">
    </div>
    <div class="mb-3">
        <label for="employee_contact" class="form-label">Contact Number</label>
        <input type="text" inputmode="numeric" pattern="\d+" class="form-control" id="employee_contact" name="employee_contact" required>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
    </div>
    <div class="mb-3">
        <label for="date_working" class="form-label">Date Working</label>
        <input type="date" class="form-control" id="date_working" name="date_working" required>
    </div>
    <div class="mb-3">
        <label for="employee_salary" class="form-label">Salary</label>
        <input type="number" step="0.01" class="form-control" id="employee_salary" name="employee_salary" required>
    </div>
    <div class="mb-3">
        <label for="employee_type" class="form-label">Type</label>
        <select class="form-control" id="employee_type" name="employee_type" required>
            <option value="">Select Type</option>
            <option value="C">Cashier</option>
            <option value="T">Trainer</option>
        </select>
    </div>
    <div id="cashier_fields" style="display:none;">
        <div class="mb-3">
            <label for="work_shift" class="form-label">Work Shift</label>
            <select class="form-control" id="work_shift" name="work_shift" required>
                <option value="">Select Work Shift</option>
                <option value="Morning (8am-4pm)">Morning Shift (8am-4pm)</option>
                <option value="Evening (3pm-11pm)">Evening Shift (3pm-11pm)</option>
            </select>
        </div>
    </div>
    <div id="trainer_fields" style="display:none;">
        <div class="mb-3">
            <label for="expertise" class="form-label">Expertise</label>
            <input type="text" class="form-control" id="expertise" name="expertise">
        </div>
        <div class="mb-3">
            <label for="certification" class="form-label">Certification</label>
            <input type="text" class="form-control" id="certification" name="certification">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<script>
document.getElementById('employee_type').addEventListener('change', function() {
    var type = this.value;
    document.getElementById('cashier_fields').style.display = (type === 'C') ? '' : 'none';
    document.getElementById('trainer_fields').style.display = (type === 'T') ? '' : 'none';
});
// Trigger on page load
document.getElementById('employee_type').dispatchEvent(new Event('change'));
</script>
<?php include '../../views/footer.php'; ?>
