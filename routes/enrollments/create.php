<?php
require_once '../../models/Enrollment.php';
require_once '../../models/Member.php';
require_once '../../models/ClassModel.php';

$memberModel = new Member();
$classModel = new ClassModel();
$members = $memberModel->getAll();
$classes = $classModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enrollment = new Enrollment();
    $data = [
        'member_id' => $_POST['member_id'],
        'class_id' => $_POST['class_id'],
        'enrollment_date' => $_POST['enrollment_date'],
        'status' => $_POST['status']
    ];
    if ($enrollment->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create enrollment.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Enrollment</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="member_id" class="form-label">Member</label>
        <select class="form-control" id="member_id" name="member_id" required>
            <option value="">Select Member</option>
            <?php foreach ($members as $m): ?>
            <option value="<?php echo $m['member_id']; ?>"><?php echo $m['first_name'] . ' ' . $m['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="class_id" class="form-label">Class</label>
        <select class="form-control" id="class_id" name="class_id" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $c): ?>
            <option value="<?php echo $c['class_id']; ?>"><?php echo $c['program_name'] . ' - ' . $c['scheduled_date'] . ' ' . $c['scheduled_time']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="enrollment_date" class="form-label">Enrollment Date</label>
        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="enrolled">Enrolled</option>
            <option value="completed">Completed</option>
            <option value="dropped">Dropped</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>