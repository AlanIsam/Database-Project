<?php
require_once '../../models/Payment.php';

$payment = new Payment();
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['delete_payment_id']) ? $_POST['delete_payment_id'] : null);

function send_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function get_related_tables($pdo, $paymentId) {
    $stmt = $pdo->prepare(
        "SELECT kcu.TABLE_NAME, kcu.COLUMN_NAME
         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
         WHERE kcu.REFERENCED_TABLE_SCHEMA = DATABASE()
           AND kcu.REFERENCED_TABLE_NAME = 'payment'
           AND kcu.REFERENCED_COLUMN_NAME = 'payment_id'"
    );
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $related = [];
    foreach ($refs as $ref) {
        $table = $ref['TABLE_NAME'];
        $col = $ref['COLUMN_NAME'];
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$col}` = ?";
        $countStmt = $pdo->prepare($sql);
        $countStmt->execute([$paymentId]);
        if ($countStmt->fetchColumn() > 0) {
            $related[] = $table;
        }
    }
    return $related;
}

// AJAX: fetch related tables
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'related') {
    $paymentId = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$paymentId) {
        send_json(['success' => false, 'error' => 'Missing payment id']);
    }
    $related = get_related_tables($payment->pdo, $paymentId);
    send_json(['success' => true, 'related' => $related]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_payment_id'])) {
        header('Content-Type: application/json');
        $deleteId = $_POST['delete_payment_id'];
        $result = $payment->delete($deleteId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            $related = get_related_tables($payment->pdo, $deleteId);
            if (!empty($related)) {
                $error = 'Failed to delete payment. This payment is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
            } else {
                $error = 'Failed to delete payment due to unknown related records.';
            }
            echo json_encode(['success' => false, 'error' => $error]);
        }
        exit;
    }
    if ($payment->delete($id)) {
        header('Location: view.php');
    } else {
        $related = get_related_tables($payment->pdo, $id);
        if (!empty($related)) {
            $error = 'Failed to delete payment. This payment is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
        } else {
            $error = 'Failed to delete payment.';
        }
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