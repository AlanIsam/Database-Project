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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?php echo $c['category_id']; ?></td>
            <td><?php echo $c['category_name']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $c['category_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-category-id="<?php echo $c['category_id']; ?>" data-category-name="<?php echo htmlspecialchars($c['category_name']); ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="deleteForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_category_id" id="delete_category_id">
                    <p>Are you sure you want to delete <span id="delete_category_name"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this category will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
                    </div>
                    <div id="deleteError" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteModal');
    var deleteForm = document.getElementById('deleteForm');
    var categoryIdInput = document.getElementById('delete_category_id');
    var categoryNameSpan = document.getElementById('delete_category_name');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(categoryId) {
        relatedTablesList.innerHTML = '...';
        fetch('delete.php?action=related&id=' + encodeURIComponent(categoryId))
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    if (data.related && data.related.length) {
                        var html = data.related.map(function(t) {
                            return '<strong>' + t + '</strong>';
                        }).join(', ');
                        relatedTablesList.innerHTML = html;
                    } else {
                        relatedTablesList.innerHTML = '<em>No related records.</em>';
                    }
                } else {
                    relatedTablesList.innerHTML = '<em>Unable to load related tables.</em>';
                }
            })
            .catch(function() {
                relatedTablesList.innerHTML = '<em>Unable to load related tables.</em>';
            });
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var categoryId = button.getAttribute('data-category-id');
        var categoryName = button.getAttribute('data-category-name');
        categoryIdInput.value = categoryId;
        categoryNameSpan.textContent = categoryName;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(categoryId);
    });

    deleteForm.onsubmit = function(e) {
        e.preventDefault();
        var formData = new FormData(deleteForm);
        fetch('delete.php', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                deleteError.innerHTML = data.error;
                deleteError.classList.remove('d-none');
            }
        })
        .catch(function() {
            deleteError.textContent = 'An error occurred.';
            deleteError.classList.remove('d-none');
        });
    };
});
</script>

<?php include '../../views/footer.php'; ?>