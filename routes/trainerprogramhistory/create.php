<?php
require_once '../../models/TrainerProgramHistory.php';
require_once '../../models/Trainer.php';
require_once '../../models/ProgramCategory.php';

$trainerModel = new Trainer();
$categoryModel = new ProgramCategory();
$trainers = $trainerModel->getAll();
$categories = $categoryModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $history = new TrainerProgramHistory();
    $data = [
        'trainer_id' => $_POST['trainer_id'],
        'category_id' => $_POST['category_id'],
        'assigned_date' => $_POST['assigned_date'],
        'end_date' => $_POST['end_date']
    ];
    if ($history->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create history.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Trainer Program History</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="trainer_id" class="form-label">Trainer</label>
        <select class="form-control" id="trainer_id" name="trainer_id" required>
            <option value="">Select Trainer</option>
            <?php foreach ($trainers as $t): ?>
            <option value="<?php echo $t['trainer_id']; ?>"><?php echo $t['first_name'] . ' ' . $t['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
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
        <label for="assigned_date" class="form-label">Assigned Date</label>
        <input type="date" class="form-control" id="assigned_date" name="assigned_date" required>
    </div>
    <div class="mb-3">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>