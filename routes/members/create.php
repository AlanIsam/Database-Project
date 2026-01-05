<?php
include '../../views/header.php';
require_once '../../models/Member.php';
require_once '../../models/Membership.php';

$membership = new Membership();
$types = $membership->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member = new Member();
    $data = [
        'member_name' => $_POST['member_name'],
        'member_ic' => $_POST['member_ic'],
        'member_contact' => $_POST['member_contact'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'membership_status' => $_POST['membership_status'],
        'type_id' => $_POST['type_id']
    ];
    try {
        $ok = $member->create($data);
        if ($ok) {
            header('Location: view.php?success=1');
            exit;
        }
        $error = 'Failed to create member.';
    } catch (Throwable $ex) {
        // No open transaction to roll back here
        $error = $ex->getMessage();
    }
}

?>
<h2>Add New Member</h2>
<?php if (isset($error)): ?>

<div class="alert alert-danger"><?php echo $error; ?></div>

<?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="member_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="member_name" name="member_name" required>
        </div>
        <div class="mb-3">
            <label for="member_ic" class="form-label">IC Number (Without "-")</label>
            <input type="text" inputmode="numeric" pattern="\d{12}" maxlength="12" class="form-control" id="member_ic" name="member_ic" required data-bs-toggle="popover" data-bs-trigger="manual" data-bs-content="must be a 12 number IC">
        </div>
        <div class="mb-3">
            <label for="member_contact" class="form-label">Contact Number</label>
            <input type="text" inputmode="numeric" pattern="\d+" class="form-control" id="member_contact" name="member_contact" required>
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
            <label for="membership_status" class="form-label">Membership Status</label>
            <select class="form-control" id="membership_status" name="membership_status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="type_id" class="form-label">Membership Type</label>
            <select class="form-control" id="type_id" name="type_id" required>
                <?php foreach ($types as $type): ?>
                <option value="<?php echo $type['type_id']; ?>"><?php echo $type['type_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>

    </form>
<?php include '../../views/footer.php'; ?>

