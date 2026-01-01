<?php
require_once '../../models/Payment.php';
require_once '../../models/Member.php';

$memberModel = new Member();
$members = $memberModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment = new Payment();
    $data = [
        'member_id' => $_POST['member_id'],
        'amount' => $_POST['amount'],
        'payment_date' => $_POST['payment_date'],
        'payment_type' => $_POST['payment_type'],
        'description' => $_POST['description']
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
            <option value="<?php echo $m['member_id']; ?>"><?php echo $m['first_name'] . ' ' . $m['last_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
    </div>
    <div class="mb-3">
        <label for="payment_date" class="form-label">Payment Date</label>
        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
    </div>
    <div class="mb-3">
        <label for="payment_type" class="form-label">Payment Type</label>
        <select class="form-control" id="payment_type" name="payment_type" required>
            <option value="membership">Membership</option>
            <option value="program">Program</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>