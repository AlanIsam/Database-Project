<?php
require_once '../../models/ProgramCategory.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = new ProgramCategory();
    $data = [
        'category_name' => $_POST['category_name']
    ];
    if ($category->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create category.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Program Category</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="category_name" class="form-label">Category Name</label>
        <input type="text" class="form-control" id="category_name" name="category_name" placeholder="e.g., Cardio, Strength Training, Yoga" required>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>