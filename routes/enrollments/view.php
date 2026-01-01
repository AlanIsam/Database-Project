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
            <th>ID</th>
            <th>Member</th>
            <th>Class</th>
            <th>Program</th>
            <th>Enrollment Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($enrollments as $e): ?>
        <tr>
            <td><?php echo $e['enrollment_id']; ?></td>
            <td><?php echo $e['first_name'] . ' ' . $e['last_name']; ?></td>
            <td><?php echo $e['scheduled_date'] . ' ' . $e['scheduled_time']; ?></td>
            <td><?php echo $e['program_name']; ?></td>
            <td><?php echo $e['enrollment_date']; ?></td>
            <td><?php echo $e['status']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $e['enrollment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $e['enrollment_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>