<?php
require_once '../../models/Trainer.php';

$trainer = new Trainer();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($trainer->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete trainer.';
    }
}

$data = $trainer->getById($id);

$content = '
<h2>Delete Trainer</h2>
<p>Are you sure you want to delete ' . $data['first_name'] . ' ' . $data['last_name'] . '?</p>
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
';

include '../../views/layout.php';
?>