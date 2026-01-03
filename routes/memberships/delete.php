<?php
require_once '../../models/Membership.php';

$membership = new Membership();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($membership->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete membership type.';
    }
}

$data = $membership->getById($id);

include '../../views/header.php';
?>
<h2>Delete Membership Type</h2>
<p>Are you sure you want to delete the membership type "<?php echo $data['type_name']; ?>" ($<?php echo number_format($data['monthly_fee'], 2); ?>/month)?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>