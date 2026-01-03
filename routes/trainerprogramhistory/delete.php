<?php
require_once '../../models/TrainerProgramHistory.php';

$history = new TrainerProgramHistory();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($history->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete history.';
    }
}

$data = $history->getById($id);

include '../../views/header.php';
?>
<h2>Delete Trainer Program History</h2>
<p>Are you sure you want to delete the history for <?php echo $data['employee_name']; ?> in <?php echo $data['category_name']; ?>?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>