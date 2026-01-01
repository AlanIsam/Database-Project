<?php
require_once '../../models/Trainer.php';

$trainer = new Trainer();
$id = $_GET['id'];
$data = $trainer->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'hire_date' => $_POST['hire_date']
    ];
    if ($trainer->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update trainer.';
    }
}

$content = '
<h2>Edit Trainer</h2>
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="' . $data['first_name'] . '" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="' . $data['last_name'] . '" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="' . $data['email'] . '" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="' . $data['phone'] . '">
    </div>
    <div class="mb-3">
        <label for="hire_date" class="form-label">Hire Date</label>
        <input type="date" class="form-control" id="hire_date" name="hire_date" value="' . $data['hire_date'] . '" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
';

include '../../views/layout.php';
?>