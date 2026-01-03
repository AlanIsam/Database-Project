<?php
require_once '../../models/ProgramCategory.php';

$category = new ProgramCategory();
$id = $_GET['id'];
$data = $category->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'category_name' => $_POST['category_name']
    ];
    if ($category->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update category.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Program Category</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="category_name" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $data['category_name']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>