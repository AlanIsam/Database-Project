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
        'class_date' => $_POST['class_date'],
        'start_time' => $_POST['start_time'],
        'end_time' => $_POST['end_time'],
        'room_number' => $_POST['room_number'],
        'program_id' => $_POST['program_id'],
        'employee_id' => $_POST['employee_id']
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
            <option value="<?php echo $p['program_id']; ?>"><?php echo $p['program_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="employee_id" class="form-label">Trainer</label>
        <select class="form-control" id="employee_id" name="employee_id" required>
            <option value="">Select Trainer</option>
            <?php foreach ($trainers as $t): ?>
            <option value="<?php echo $t['employee_id']; ?>"><?php echo $t['employee_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="class_date" class="form-label">Class Date</label>
        <input type="date" class="form-control" id="class_date" name="class_date" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="time" class="form-control" id="start_time" name="start_time" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="time" class="form-control" id="end_time" name="end_time" required>
    </div>
    <div class="mb-3">
        <label for="room_number" class="form-label">Room Number</label>
        <input type="text" class="form-control" id="room_number" name="room_number" placeholder="e.g., Room 1">
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>