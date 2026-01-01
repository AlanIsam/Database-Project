<?php
require_once '../../models/Member.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member = new Member();
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'join_date' => $_POST['join_date'],
        'membership_status' => $_POST['membership_status']
    ];
    if ($member->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create member.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Member</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <div class="mb-3">
        <label for="join_date" class="form-label">Join Date</label>
        <input type="date" class="form-control" id="join_date" name="join_date" required>
    </div>
    <div class="mb-3">
        <label for="membership_status" class="form-label">Membership Status</label>
        <select class="form-control" id="membership_status" name="membership_status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>