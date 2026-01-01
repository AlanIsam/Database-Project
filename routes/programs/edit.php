<?php
require_once '../../models/Program.php';
require_once '../../models/ProgramCategory.php';

$program = new Program();
$categoryModel = new ProgramCategory();
$categories = $categoryModel->getAll();
$id = $_GET['id'];
$data = $program->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'name' => $_POST['name'],
        'category_id' => $_POST['category_id'],
        'description' => $_POST['description'],
        'duration_weeks' => $_POST['duration_weeks'],
        'fee' => $_POST['fee']
    ];
    if ($program->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update program.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Program</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $data['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['category_id']; ?>" <?php echo ($c['category_id'] == $data['category_id']) ? 'selected' : ''; ?>><?php echo $c['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"><?php echo $data['description']; ?></textarea>
    </div>
    <div class="mb-3">
        <label for="duration_weeks" class="form-label">Duration (Weeks)</label>
        <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" value="<?php echo $data['duration_weeks']; ?>">
    </div>
    <div class="mb-3">
        <label for="fee" class="form-label">Fee</label>
        <input type="number" step="0.01" class="form-control" id="fee" name="fee" value="<?php echo $data['fee']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>