<?php
require_once '../../models/Payment.php';
$payment = new Payment();
$payments = $payment->getAll();

include '../../views/header.php';
?>
<h2>Payments</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Payment</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Member</th>
            <th>Cashier</th>
            <th>Amount</th>
            <th>Payment Date</th>
            <th>Method</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $p): ?>
        <tr>
            <td><?php echo $p['payment_id']; ?></td>
            <td><?php echo $p['member_name']; ?></td>
            <td><?php echo $p['cashier_name']; ?></td>
            <td><?php echo $p['payment_amount']; ?></td>
            <td><?php echo $p['payment_date']; ?></td>
            <td><?php echo $p['payment_method']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $p['payment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $p['payment_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>