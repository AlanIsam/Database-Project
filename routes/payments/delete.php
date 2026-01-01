<?php
require_once '../../models/Payment.php';

$payment = new Payment();
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($payment->delete($id)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to delete payment.';
    }
}

$data = $payment->getById($id);

include '../../views/header.php';
?>
<h2>Delete Payment</h2>
<p>Are you sure you want to delete the payment of $<?php echo $data['amount']; ?> for <?php echo $data['first_name'] . ' ' . $data['last_name']; ?>?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>