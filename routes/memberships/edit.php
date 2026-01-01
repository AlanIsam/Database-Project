<?php
require_once '../../models/Membership.php';
require_once '../../models/Member.php';

$membership = new Membership();
$memberModel = new Member();
$members = $memberModel->getAll();
$id = $_GET['id'];
$data = $membership->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'member_id' => $_POST['member_id'],
        'membership_type' => $_POST['membership_type'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'fee' => $_POST['fee']
    ];
    if ($membership->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update membership.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Membership</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="member_id" class="form-label">Member</label>
        <select class="form-control" id="member_id" name="member_id" required>
            <option value="">Select Member</option>
            <?php foreach ($members as $m): ?>
            <option value="<?php echo $m['member_id']; ?>" <?php echo ($m['member_id'] == $data['member_id']) ? 'selected' : ''; ?>><?php echo $m['first_name'] . ' ' . $m['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="membership_type" class="form-label">Membership Type</label>
        <input type="text" class="form-control" id="membership_type" name="membership_type" value="<?php echo $data['membership_type']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['start_date']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['end_date']; ?>">
    </div>
    <div class="mb-3">
        <label for="fee" class="form-label">Fee</label>
        <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="<?php echo $data['fee']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>