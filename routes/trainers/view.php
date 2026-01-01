<?php
require_once '../../models/Trainer.php';
$trainer = new Trainer();
$trainers = $trainer->getAll();

include '../../views/header.php';
?>
<h2>Trainers</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Trainer</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Hire Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($trainers as $t): ?>
        <tr>
            <td><?php echo $t['trainer_id']; ?></td>
            <td><?php echo $t['first_name'] . ' ' . $t['last_name']; ?></td>
            <td><?php echo $t['email']; ?></td>
            <td><?php echo $t['phone']; ?></td>
            <td><?php echo $t['hire_date']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $t['trainer_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $t['trainer_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>