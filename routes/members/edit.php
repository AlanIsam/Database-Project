<?php
require_once '../../models/Member.php';

$member = new Member();
$id = $_GET['id'];
$data = $member->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'join_date' => $_POST['join_date'],
        'membership_status' => $_POST['membership_status']
    ];
    if ($member->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update member.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Member</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $data['first_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $data['last_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $data['email']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $data['phone']; ?>">
    </div>
    <div class="mb-3">
        <label for="join_date" class="form-label">Join Date</label>
        <input type="date" class="form-control" id="join_date" name="join_date" value="<?php echo $data['join_date']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="membership_status" class="form-label">Membership Status</label>
        <select class="form-control" id="membership_status" name="membership_status">
            <option value="active" <?php echo ($data['membership_status'] == 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo ($data['membership_status'] == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>