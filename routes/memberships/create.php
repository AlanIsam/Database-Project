<?php
require_once '../../models/Membership.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $membership = new Membership();
    $data = [
        'type_name' => $_POST['type_name'],
        'monthly_fee' => $_POST['monthly_fee']
    ];
    if ($membership->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create membership type.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Membership Type</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="type_name" class="form-label">Type Name</label>
        <input type="text" class="form-control" id="type_name" name="type_name" placeholder="e.g., Premium, Basic, Student" required>
    </div>
    <div class="mb-3">
        <label for="monthly_fee" class="form-label">Monthly Fee</label>
        <input type="number" step="0.01" class="form-control" id="monthly_fee" name="monthly_fee" placeholder="0.00" required>
    </div>
    <div class="mb-3">
        <label for="fee" class="form-label">Fee</label>
        <input type="number" step="0.01" class="form-control" id="fee" name="fee" required>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>