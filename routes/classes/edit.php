<?php
require_once '../../models/ClassModel.php';
require_once '../../models/Program.php';
require_once '../../models/Trainer.php';

$classModel = new ClassModel();
$programModel = new Program();
$trainerModel = new Trainer();
$programs = $programModel->getAll();
$trainers = $trainerModel->getAll();
$id = $_GET['id'];
$data = $classModel->getById($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateData = [
        'class_date' => $_POST['class_date'],
        'start_time' => $_POST['start_time'],
        'end_time' => $_POST['end_time'],
        'room_number' => $_POST['room_number'],
        'program_id' => $_POST['program_id'],
        'employee_id' => $_POST['employee_id'],
        'class_status' => $_POST['class_status']
    ];
    if ($classModel->update($id, $updateData)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to update class.';
    }
}

include '../../views/header.php';
?>
<h2>Edit Class</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="program_id" class="form-label">Program</label>
        <select class="form-control" id="program_id" name="program_id" required>
            <option value="">Select Program</option>
            <?php foreach ($programs as $p): ?>
            <option value="<?php echo $p['program_id']; ?>" <?php echo ($p['program_id'] == $data['program_id']) ? 'selected' : ''; ?>><?php echo $p['program_name']; ?></option>
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
        <label for="class_date" class="form-label">Class Date</label>
        <input type="date" class="form-control" id="class_date" name="class_date" value="<?php echo $data['class_date']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo $data['start_time']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo $data['end_time']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="room_number" class="form-label">Room Number</label>
        <input type="text" class="form-control" id="room_number" name="room_number" value="<?php echo $data['room_number']; ?>">
    </div>
    <div class="mb-3">
        <label for="class_status" class="form-label">Status</label>
        <select class="form-control" id="class_status" name="class_status" required>
            <option value="">Select Status</option>
            <option value="Active" <?php echo ($data['class_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
            <option value="Completed" <?php echo ($data['class_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?php echo ($data['class_status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>
