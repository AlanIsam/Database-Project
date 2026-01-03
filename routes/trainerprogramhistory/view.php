<?php
require_once '../../models/TrainerProgramHistory.php';
$history = new TrainerProgramHistory();
$histories = $history->getAll();

include '../../views/header.php';
?>
<h2>Trainer Program History</h2>
<a href="create.php" class="btn btn-success mb-3">Add New History</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($histories as $h): ?>
        <tr>
            <td><?php echo $h['history_id']; ?></td>
            <td><?php echo $h['employee_name']; ?></td>
            <td><?php echo $h['category_name']; ?></td>
            <td><?php echo $h['start_date']; ?></td>
            <td><?php echo $h['end_date'] ?? 'Current'; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $h['history_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $h['history_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>