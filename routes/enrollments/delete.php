<?php
require_once '../../models/Enrollment.php';

$enrollment = new Enrollment();
$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : (isset($_POST['class_id']) ? $_POST['class_id'] : null);
$member_id = isset($_GET['member_id']) ? $_GET['member_id'] : (isset($_POST['member_id']) ? $_POST['member_id'] : null);

function send_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function get_related_tables($pdo, $classId, $memberId) {
    // Find tables referencing class_member on (class_id, member_id) or either column; best-effort
    $stmt = $pdo->prepare(
        "SELECT TABLE_NAME, COLUMN_NAME
         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
         WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
           AND REFERENCED_TABLE_NAME = 'class_member'"
    );
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $related = [];
    foreach ($refs as $ref) {
        $table = $ref['TABLE_NAME'];
        $col = $ref['COLUMN_NAME'];
        // Try to match either class_id or member_id columns
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$col}` IN (?, ?)";
        $countStmt = $pdo->prepare($sql);
        $countStmt->execute([$classId, $memberId]);
        if ($countStmt->fetchColumn() > 0) {
            $related[] = $table;
        }
    }
    return $related;
}

// AJAX: fetch related tables
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'related') {
    if (!$class_id || !$member_id) {
        send_json(['success' => false, 'error' => 'Missing class or member id']);
    }
    $related = get_related_tables($enrollment->pdo, $class_id, $member_id);
    send_json(['success' => true, 'related' => $related]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['class_id']) && isset($_POST['member_id'])) {
        header('Content-Type: application/json');
        $deleteClass = $_POST['class_id'];
        $deleteMember = $_POST['member_id'];
        $result = $enrollment->delete($deleteClass, $deleteMember);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            $related = get_related_tables($enrollment->pdo, $deleteClass, $deleteMember);
            if (!empty($related)) {
                $error = 'Failed to delete enrollment. It is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
            } else {
                $error = 'Failed to delete enrollment due to unknown related records.';
            }
            echo json_encode(['success' => false, 'error' => $error]);
        }
        exit;
    }
    if ($enrollment->delete($class_id, $member_id)) {
        header('Location: view.php');
    } else {
        $related = get_related_tables($enrollment->pdo, $class_id, $member_id);
        if (!empty($related)) {
            $error = 'Failed to delete enrollment. It is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
        } else {
            $error = 'Failed to delete enrollment.';
        }
    }
}

include '../../views/header.php';
?>
<h2>Delete Enrollment</h2>
<p>Are you sure you want to delete this enrollment?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>