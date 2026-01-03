<?php
require_once '../../models/Membership.php';
$membership = new Membership();
$memberships = $membership->getAll();

include '../../views/header.php';
?>
<h2>Membership Types</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Membership Type</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type Name</th>
            <th>Monthly Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($memberships as $ms): ?>
        <tr>
            <td><?php echo $ms['type_id']; ?></td>
            <td><?php echo $ms['type_name']; ?></td>
            <td>$<?php echo number_format($ms['monthly_fee'], 2); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $ms['type_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $ms['type_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>