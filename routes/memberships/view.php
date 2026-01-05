<?php
require_once '../../models/Membership.php';
$membership = new Membership();
$memberships = $membership->getAll();

include '../../views/header.php';
?>
<h2>Membership Types</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Membership Type</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type Name</th>
            <th>Monthly Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($memberships as $ms): ?>
        <tr>
            <td><?php echo $ms['type_id']; ?></td>
            <td><?php echo $ms['type_name']; ?></td>
            <td>$<?php echo number_format($ms['monthly_fee'], 2); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $ms['type_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-type-id="<?php echo $ms['type_id']; ?>" data-type-name="<?php echo htmlspecialchars($ms['type_name']); ?>">Delete</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Membership Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_membership_id" id="delete_membership_id">
                    <p>Are you sure you want to delete <span id="delete_type_name"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this membership type will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
    var typeIdInput = document.getElementById('delete_membership_id');
    var typeNameSpan = document.getElementById('delete_type_name');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(typeId) {
        relatedTablesList.innerHTML = '...';
        fetch('delete.php?action=related&id=' + encodeURIComponent(typeId))
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
        var typeId = button.getAttribute('data-type-id');
        var typeName = button.getAttribute('data-type-name');
        typeIdInput.value = typeId;
        typeNameSpan.textContent = typeName;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(typeId);
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