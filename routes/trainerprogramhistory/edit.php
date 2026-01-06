<?php
require_once '../../models/TrainerProgramHistory.php';
require_once '../../models/Trainer.php';
require_once '../../models/ProgramCategory.php';

$history = new TrainerProgramHistory();
$trainerModel = new Trainer();
$categoryModel = new ProgramCategory();
$trainers = $trainerModel->getAll();
$categories = $categoryModel->getAll();
$id = $_GET['id'];
$data = $history->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'employee_id' => $_POST['employee_id'],
        'program_category' => $_POST['program_category'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date']
    ];
    if ($history->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update history.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Trainer Program History</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
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
        <label for="program_category" class="form-label">Category</label>
        <select class="form-control" id="program_category" name="program_category" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['category_name']; ?>" <?php echo ($c['category_name'] == $data['program_category']) ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $data['start_date']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $data['end_date']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>