<?php
require_once '../../models/ClassModel.php';

$classModel = new ClassModel();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($classModel->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete class.';
    }
}

$data = $classModel->getById($id);

include '../../views/header.php';
?>
<h2>Delete Class</h2>
<p>Are you sure you want to delete the class for <?php echo $data['program_name']; ?> on <?php echo $data['scheduled_date']; ?>?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>