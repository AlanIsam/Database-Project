<?php
require_once '../../models/ProgramCategory.php';

$category = new ProgramCategory();
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['delete_category_id']) ? $_POST['delete_category_id'] : null);

function send_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function get_related_tables($pdo, $categoryId) {
    $stmt = $pdo->prepare(
        "SELECT kcu.TABLE_NAME, kcu.COLUMN_NAME
         FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
         WHERE kcu.REFERENCED_TABLE_SCHEMA = DATABASE()
           AND kcu.REFERENCED_TABLE_NAME = 'program_category'
           AND kcu.REFERENCED_COLUMN_NAME = 'category_id'"
    );
    $stmt->execute();
    $refs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $related = [];
    foreach ($refs as $ref) {
        $table = $ref['TABLE_NAME'];
        $col = $ref['COLUMN_NAME'];
        $sql = "SELECT COUNT(*) FROM `{$table}` WHERE `{$col}` = ?";
        $countStmt = $pdo->prepare($sql);
        $countStmt->execute([$categoryId]);
        if ($countStmt->fetchColumn() > 0) {
            $related[] = $table;
        }
    }
    return $related;
}

// AJAX: fetch related tables
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'related') {
    $categoryId = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$categoryId) {
        send_json(['success' => false, 'error' => 'Missing category id']);
    }
    $related = get_related_tables($category->pdo, $categoryId);
    send_json(['success' => true, 'related' => $related]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_category_id'])) {
        header('Content-Type: application/json');
        $deleteId = $_POST['delete_category_id'];
        $result = $category->delete($deleteId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            $related = get_related_tables($category->pdo, $deleteId);
            if (!empty($related)) {
                $error = 'Failed to delete category. This category is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
            } else {
                $error = 'Failed to delete category due to unknown related records.';
            }
            echo json_encode(['success' => false, 'error' => $error]);
        }
        exit;
    }
    if ($category->delete($id)) {
        header('Location: view.php');
    } else {
        $related = get_related_tables($category->pdo, $id);
        if (!empty($related)) {
            $error = 'Failed to delete category. This category is still referenced in the following tables: <ul><li>' . implode('</li><li>', $related) . '</li></ul>Please remove related records first.';
        } else {
            $error = 'Failed to delete category.';
        }
    }
}

$data = $category->getById($id);

include '../../views/header.php';
?>
<h2>Delete Program Category</h2>
<p>Are you sure you want to delete the category "<?php echo $data['category_name']; ?>"?</p>
<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post">
    <button type="submit" class="btn btn-danger">Delete</button>
    <a href="view.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../../views/footer.php'; ?>