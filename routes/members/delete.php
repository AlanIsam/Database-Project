<?php
require_once '../../models/Member.php';

$member = new Member();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($member->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete member.';
    }
}

$data = $member->getById($id);

include '../../views/header.php';
?>
<h2>Delete Member</h2>
<p>Are you sure you want to delete <?php echo $data['first_name'] . ' ' . $data['last_name']; ?>?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>