<?php
require_once '../../models/Enrollment.php';
$enrollment = new Enrollment();
$enrollments = $enrollment->getAll();

include '../../views/header.php';
?>
<h2>Enrollments</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Enrollment</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Member</th>
            <th>Class Date</th>
            <th>Class Time</th>
            <th>Program</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($enrollments as $e): ?>
        <tr>
            <td><?php echo $e['member_name']; ?></td>
            <td><?php echo $e['class_date']; ?></td>
            <td><?php echo $e['start_time'] . ' - ' . $e['end_time']; ?></td>
            <td><?php echo $e['program_name']; ?></td>
            <td>
                <a href="delete.php?class_id=<?php echo $e['class_id']; ?>&member_id=<?php echo $e['member_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>