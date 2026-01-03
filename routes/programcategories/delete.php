<?php
require_once '../../models/ProgramCategory.php';

$category = new ProgramCategory();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($category->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete category.';
    }
}

$data = $category->getById($id);

include '../../views/header.php';
?>
<h2>Delete Program Category</h2>
<p>Are you sure you want to delete the category "<?php echo $data['category_name']; ?>"?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>