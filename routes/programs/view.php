<?php
require_once '../../models/Program.php';
$program = new Program();
$programs = $program->getAll();

include '../../views/header.php';
?>
<h2>Programs</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Program</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Trainer</th>
            <th>Duration</th>
            <th>Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($programs as $p): ?>
        <tr>
            <td><?php echo $p['program_id']; ?></td>
            <td><?php echo $p['program_name']; ?></td>
            <td><?php echo $p['category_name']; ?></td>
            <td><?php echo $p['trainer_name']; ?></td>
            <td><?php echo $p['program_duration']; ?></td>
            <td><?php echo $p['program_fee']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $p['program_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $p['program_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>