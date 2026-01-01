<?php
require_once '../../models/Membership.php';
$membership = new Membership();
$memberships = $membership->getAll();

include '../../views/header.php';
?>
<h2>Memberships</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Membership</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Member</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($memberships as $ms): ?>
        <tr>
            <td><?php echo $ms['membership_id']; ?></td>
            <td><?php echo $ms['first_name'] . ' ' . $ms['last_name']; ?></td>
            <td><?php echo $ms['membership_type']; ?></td>
            <td><?php echo $ms['start_date']; ?></td>
            <td><?php echo $ms['end_date']; ?></td>
            <td><?php echo $ms['fee']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $ms['membership_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $ms['membership_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>