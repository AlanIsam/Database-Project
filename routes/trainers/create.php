<?php
require_once '../../models/Trainer.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trainer = new Trainer();
    $data = [
        'employee_name' => $_POST['employee_name'],
        'employee_ic' => $_POST['employee_ic'],
        'employee_contact' => $_POST['employee_contact'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'date_working' => $_POST['date_working'],
        'employee_salary' => $_POST['employee_salary'],
        'expertise' => $_POST['expertise'],
        'certification' => $_POST['certification']
    ];
    if ($trainer->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create trainer.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Trainer</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="employee_name" class="form-label">Name</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
    </div>
    <div class="mb-3">
        <label for="employee_ic" class="form-label">IC Number</label>
        <input type="text" class="form-control" id="employee_ic" name="employee_ic" required>
    </div>
    <div class="mb-3">
        <label for="employee_contact" class="form-label">Contact</label>
        <input type="text" class="form-control" id="employee_contact" name="employee_contact" required>
    </div>
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-control" id="gender" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
    </div>
    <div class="mb-3">
        <label for="date_working" class="form-label">Date Working</label>
        <input type="date" class="form-control" id="date_working" name="date_working" required>
    </div>
    <div class="mb-3">
        <label for="employee_salary" class="form-label">Salary</label>
        <input type="number" step="0.01" class="form-control" id="employee_salary" name="employee_salary">
    </div>
    <div class="mb-3">
        <label for="expertise" class="form-label">Expertise</label>
        <input type="text" class="form-control" id="expertise" name="expertise">
    </div>
    <div class="mb-3">
        <label for="certification" class="form-label">Certification</label>
        <input type="text" class="form-control" id="certification" name="certification">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>