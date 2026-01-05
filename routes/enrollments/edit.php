<?php
require_once '../../models/Enrollment.php';
require_once '../../models/Member.php';
require_once '../../models/ClassModel.php';

$enrollmentModel = new Enrollment();
$memberModel = new Member();
$classModel = new ClassModel();

$members = $memberModel->getAll();
$classes = $classModel->getAll();

// Identify the enrollment to edit using composite key
$classIdParam = isset($_GET['class_id']) ? $_GET['class_id'] : null;
$memberIdParam = isset($_GET['member_id']) ? $_GET['member_id'] : null;

// When posting, trust the hidden original ids; otherwise, rely on query params
$originalClassId = isset($_POST['original_class_id']) ? $_POST['original_class_id'] : $classIdParam;
$originalMemberId = isset($_POST['original_member_id']) ? $_POST['original_member_id'] : $memberIdParam;

if (!$originalClassId || !$originalMemberId) {
	header('Location: view.php');
	exit();
}

$currentEnrollment = $enrollmentModel->getOne($originalClassId, $originalMemberId);
if (!$currentEnrollment) {
	header('Location: view.php');
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$updateData = [
		'class_id' => $_POST['class_id'],
		'member_id' => $_POST['member_id']
	];

	if ($enrollmentModel->update($originalClassId, $originalMemberId, $updateData)) {
		header('Location: view.php');
		exit();
	} else {
		$error = 'Failed to update enrollment. It may already exist or violate constraints.';
		// keep form selections to what user submitted
		$currentEnrollment['class_id'] = $updateData['class_id'];
		$currentEnrollment['member_id'] = $updateData['member_id'];
	}
}

include '../../views/header.php';
?>
<h2>Edit Enrollment</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
	<input type="hidden" name="original_class_id" value="<?php echo htmlspecialchars($originalClassId); ?>">
	<input type="hidden" name="original_member_id" value="<?php echo htmlspecialchars($originalMemberId); ?>">
	<div class="mb-3">
		<label for="member_id" class="form-label">Member</label>
		<select class="form-control" id="member_id" name="member_id" required>
			<option value="">Select Member</option>
			<?php foreach ($members as $m): ?>
			<option value="<?php echo $m['member_id']; ?>" <?php echo ($m['member_id'] == $currentEnrollment['member_id']) ? 'selected' : ''; ?>><?php echo $m['member_name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="mb-3">
		<label for="class_id" class="form-label">Class</label>
		<select class="form-control" id="class_id" name="class_id" required>
			<option value="">Select Class</option>
			<?php foreach ($classes as $c): ?>
			<option value="<?php echo $c['class_id']; ?>" <?php echo ($c['class_id'] == $currentEnrollment['class_id']) ? 'selected' : ''; ?>><?php echo $c['program_name'] . ' - ' . $c['class_date'] . ' ' . $c['start_time']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<button type="submit" class="btn btn-primary">Update</button>
	<a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>