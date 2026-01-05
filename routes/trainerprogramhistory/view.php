<?php
require_once '../../models/TrainerProgramHistory.php';
$history = new TrainerProgramHistory();
$histories = $history->getAll();

include '../../views/header.php';
?>
<h2>Trainer Program History</h2>
<a href="create.php" class="btn btn-success mb-3">Add New History</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($histories as $h): ?>
        <tr>
            <td><?php echo $h['history_id']; ?></td>
            <td><?php echo $h['employee_name']; ?></td>
            <td><?php echo $h['category_name']; ?></td>
            <td><?php echo $h['start_date']; ?></td>
            <td><?php echo $h['end_date'] ?? 'Current'; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $h['history_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-history-id="<?php echo $h['history_id']; ?>" data-history-label="<?php echo htmlspecialchars($h['employee_name'] . ' - ' . $h['category_name']); ?>">Delete</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Trainer Program History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_history_id" id="delete_history_id">
                    <p>Are you sure you want to delete <span id="delete_history_label"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this history will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
    var historyIdInput = document.getElementById('delete_history_id');
    var historyLabelSpan = document.getElementById('delete_history_label');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(historyId) {
        relatedTablesList.innerHTML = '...';
        fetch('delete.php?action=related&id=' + encodeURIComponent(historyId))
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
        var historyId = button.getAttribute('data-history-id');
        var label = button.getAttribute('data-history-label');
        historyIdInput.value = historyId;
        historyLabelSpan.textContent = label;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(historyId);
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