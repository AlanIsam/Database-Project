<?php
require_once '../../models/Member.php';
$member = new Member();
$members = $member->getAll();

include '../../views/header.php';
?>
<h2>Members</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Member</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>IC</th>
            <th>Contact</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Membership Status</th>
            <th>Membership Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
            <td><?php echo $m['member_id']; ?></td>
            <td><?php echo $m['member_name']; ?></td>
            <td><?php echo $m['member_ic']; ?></td>
            <td><?php echo $m['member_contact']; ?></td>
            <td><?php echo $m['gender']; ?></td>
            <td><?php echo $m['date_of_birth']; ?></td>
            <td><?php echo $m['membership_status']; ?></td>
            <td><?php echo $m['type_name']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $m['member_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $m['member_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>