<?php
require_once '../../models/Payment.php';
require_once '../../models/Member.php';
require_once '../../models/Cashier.php';

$memberModel = new Member();
$members = $memberModel->getAll();
$cashierModel = new Cashier();
$cashiers = $cashierModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment = new Payment();
    $data = [
        'payment_date' => $_POST['payment_date'],
        'payment_amount' => $_POST['payment_amount'],
        'payment_method' => $_POST['payment_method'],
        'member_id' => $_POST['member_id'],
        'employee_id' => $_POST['employee_id']
    ];
    if ($payment->create($data)) {
        header('Location: view.php');
    } else {
        $error = 'Failed to create payment.';
    }
}

include '../../views/header.php';
?>
<h2>Add New Payment</h2>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label for="member_id" class="form-label">Member</label>
        <select class="form-control" id="member_id" name="member_id" required>
            <option value="">Select Member</option>
            <?php foreach ($members as $m): ?>
            <option value="<?php echo $m['member_id']; ?>"><?php echo $m['member_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="employee_id" class="form-label">Cashier</label>
        <select class="form-control" id="employee_id" name="employee_id" required>
            <option value="">Select Cashier</option>
            <?php foreach ($cashiers as $c): ?>
            <option value="<?php echo $c['employee_id']; ?>"><?php echo $c['employee_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="payment_amount" class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control" id="payment_amount" name="payment_amount" required>
    </div>
    <div class="mb-3">
        <label for="payment_date" class="form-label">Payment Date</label>
        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
    </div>
    <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select class="form-control" id="payment_method" name="payment_method" required>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>