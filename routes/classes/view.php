<?php
require_once '../../models/ClassModel.php';
$classModel = new ClassModel();
$classes = $classModel->getAll();

include '../../views/header.php';
?>
<h2>Classes</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Class</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Program</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classes as $c): ?>
        <tr>
            <td><?php echo $c['class_id']; ?></td>
            <td><?php echo $c['program_name']; ?></td>
            <td><?php echo $c['first_name'] . ' ' . $c['last_name']; ?></td>
            <td><?php echo $c['category_name']; ?></td>
            <td><?php echo $c['scheduled_date']; ?></td>
            <td><?php echo $c['scheduled_time']; ?></td>
            <td><?php echo $c['status']; ?></td>
            <td><?php echo $c['capacity']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $c['class_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $c['class_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>