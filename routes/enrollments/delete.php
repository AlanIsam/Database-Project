<?php
require_once '../../models/Enrollment.php';

$enrollment = new Enrollment();
$class_id = $_GET['class_id'];
$member_id = $_GET['member_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($enrollment->delete($class_id, $member_id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete enrollment.';
    }
}

include '../../views/header.php';
?>
<h2>Delete Enrollment</h2>
<p>Are you sure you want to delete this enrollment?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>