<?php
require_once '../../models/Program.php';
require_once '../../models/ProgramCategory.php';
require_once '../../models/Trainer.php';

$program = new Program();
$categoryModel = new ProgramCategory();
$categories = $categoryModel->getAll();
$trainerModel = new Trainer();
$trainers = $trainerModel->getAll();
$id = $_GET['id'];
$data = $program->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'program_name' => $_POST['program_name'],
        'program_duration' => $_POST['program_duration'],
        'program_fee' => $_POST['program_fee'],
        'employee_id' => $_POST['employee_id'],
        'category_id' => $_POST['category_id']
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
        <label for="program_name" class="form-label">Name</label>
        <input type="text" class="form-control" id="program_name" name="program_name" value="<?php echo $data['program_name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select class="form-control" id="category_id" name="category_id">
            <option value="">Select Category</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['category_id']; ?>" <?php echo ($c['category_id'] == $data['category_id']) ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="employee_id" class="form-label">Trainer</label>
        <select class="form-control" id="employee_id" name="employee_id" required>
            <option value="">Select Trainer</option>
            <?php foreach ($trainers as $t): ?>
            <option value="<?php echo $t['employee_id']; ?>" <?php echo ($t['employee_id'] == $data['employee_id']) ? 'selected' : ''; ?>><?php echo $t['employee_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="program_duration" class="form-label">Duration</label>
        <input type="text" class="form-control" id="program_duration" name="program_duration" value="<?php echo $data['program_duration']; ?>">
    </div>
    <div class="mb-3">
        <label for="program_fee" class="form-label">Fee</label>
        <input type="number" step="0.01" class="form-control" id="program_fee" name="program_fee" value="<?php echo $data['program_fee']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>