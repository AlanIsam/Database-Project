<?php
require_once '../../models/Employee.php';
$employee = new Employee();
$id = $_GET['id'];
$data = $employee->getById($id);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $update = [
        'employee_name' => $_POST['employee_name'],
        'employee_ic' => $_POST['employee_ic'],
        'employee_contact' => $_POST['employee_contact'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'date_working' => $_POST['date_working'],
        'employee_salary' => $_POST['employee_salary'],
        'employee_type' => $_POST['employee_type']
    ];
    if ($employee->update($id, $update)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update employee.';
    }
}
include '../../views/header.php';
?>
<h2>Edit Employee</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="employee_name" class="form-label">Name</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" value="<?php echo htmlspecialchars($data['employee_name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="employee_ic" class="form-label">IC</label>
        <input type="text" class="form-control" id="employee_ic" name="employee_ic" value="<?php echo htmlspecialchars($data['employee_ic']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="employee_contact" class="form-label">Contact</label>
        <input type="text" class="form-control" id="employee_contact" name="employee_contact" value="<?php echo htmlspecialchars($data['employee_contact']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="Male" <?php if ($data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($data['date_of_birth']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="date_working" class="form-label">Date Working</label>
        <input type="date" class="form-control" id="date_working" name="date_working" value="<?php echo htmlspecialchars($data['date_working']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="employee_salary" class="form-label">Salary</label>
        <input type="number" step="0.01" class="form-control" id="employee_salary" name="employee_salary" value="<?php echo htmlspecialchars($data['employee_salary']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="employee_type" class="form-label">Type</label>
        <select class="form-control" id="employee_type" name="employee_type" required>
            <option value="C" <?php if ($data['employee_type'] == 'C') echo 'selected'; ?>>Cashier</option>
            <option value="T" <?php if ($data['employee_type'] == 'T') echo 'selected'; ?>>Trainer</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>
