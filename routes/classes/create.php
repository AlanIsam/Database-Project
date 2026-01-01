<?php
require_once '../../models/ClassModel.php';
require_once '../../models/Program.php';
require_once '../../models/Trainer.php';

$programModel = new Program();
$trainerModel = new Trainer();
$programs = $programModel->getAll();
$trainers = $trainerModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $classModel = new ClassModel();
    $data = [
        'program_id' => $_POST['program_id'],
        'trainer_id' => $_POST['trainer_id'],
        'scheduled_date' => $_POST['scheduled_date'],
        'scheduled_time' => $_POST['scheduled_time'],
        'status' => $_POST['status'],
        'capacity' => $_POST['capacity']
    ];
    if ($classModel->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create class.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Class</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="program_id" class="form-label">Program</label>
        <select class="form-control" id="program_id" name="program_id" required>
            <option value="">Select Program</option>
            <?php foreach ($programs as $p): ?>
            <option value="<?php echo $p['program_id']; ?>"><?php echo $p['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
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
        <label for="scheduled_date" class="form-label">Scheduled Date</label>
        <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
    </div>
    <div class="mb-3">
        <label for="scheduled_time" class="form-label">Scheduled Time</label>
        <input type="time" class="form-control" id="scheduled_time" name="scheduled_time" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="active">Active</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="capacity" class="form-label">Capacity</label>
        <input type="number" class="form-control" id="capacity" name="capacity" value="20">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>