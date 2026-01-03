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
            <th>IC</th>
            <th>Contact</th>
            <th>Expertise</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($trainers as $t): ?>
        <tr>
            <td><?php echo $t['employee_id']; ?></td>
            <td><?php echo $t['employee_name']; ?></td>
            <td><?php echo $t['employee_ic']; ?></td>
            <td><?php echo $t['employee_contact']; ?></td>
            <td><?php echo $t['expertise']; ?></td>
            <td><?php echo $t['employee_salary']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $t['employee_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $t['employee_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>