<?php
require_once '../../models/Program.php';
require_once '../../models/ProgramCategory.php';

$categoryModel = new ProgramCategory();
$categories = $categoryModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $program = new Program();
    $data = [
        'name' => $_POST['name'],
        'category_id' => $_POST['category_id'],
        'description' => $_POST['description'],
        'duration_weeks' => $_POST['duration_weeks'],
        'fee' => $_POST['fee']
    ];
    if ($program->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create program.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Program</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['category_id']; ?>"><?php echo $c['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <div class="mb-3">
        <label for="duration_weeks" class="form-label">Duration (Weeks)</label>
        <input type="number" class="form-control" id="duration_weeks" name="duration_weeks">
    </div>
    <div class="mb-3">
        <label for="fee" class="form-label">Fee</label>
        <input type="number" step="0.01" class="form-control" id="fee" name="fee">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>