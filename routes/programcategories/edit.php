<?php
require_once '../../models/ProgramCategory.php';

$category = new ProgramCategory();
$id = $_GET['id'];
$data = $category->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'name' => $_POST['name'],
        'description' => $_POST['description']
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
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"><?php echo $data['description']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>