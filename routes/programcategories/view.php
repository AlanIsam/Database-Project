<?php
require_once '../../models/ProgramCategory.php';
$category = new ProgramCategory();
$categories = $category->getAll();

include '../../views/header.php';
?>
<h2>Program Categories</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Category</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?php echo $c['category_id']; ?></td>
            <td><?php echo $c['name']; ?></td>
            <td><?php echo $c['description']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $c['category_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete.php?id=<?php echo $c['category_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../../views/footer.php'; ?>