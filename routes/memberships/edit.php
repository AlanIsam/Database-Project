<?php
require_once '../../models/Membership.php';

$membership = new Membership();
$id = $_GET['id'];
$data = $membership->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'type_name' => $_POST['type_name'],
        'monthly_fee' => $_POST['monthly_fee']
    ];
    if ($membership->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update membership type.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Membership Type</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="type_name" class="form-label">Type Name</label>
        <input type="text" class="form-control" id="type_name" name="type_name" value="<?php echo $data['type_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="monthly_fee" class="form-label">Monthly Fee</label>
        <input type="number" step="0.01" class="form-control" id="monthly_fee" name="monthly_fee" value="<?php echo $data['monthly_fee']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>