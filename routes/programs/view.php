<?php
require_once '../../models/Program.php';
$program = new Program();
$programs = $program->getAll();

include '../../views/header.php';
?>
<h2>Programs</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Program</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Trainer</th>
            <th>Duration</th>
            <th>Fee</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($programs as $p): ?>
        <tr>
            <td><?php echo $p['program_id']; ?></td>
            <td><?php echo $p['program_name']; ?></td>
            <td><?php echo $p['category_name']; ?></td>
            <td><?php echo $p['trainer_name']; ?></td>
            <td><?php echo $p['program_duration']; ?></td>
            <td><?php echo $p['program_fee']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $p['program_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-program-id="<?php echo $p['program_id']; ?>" data-program-name="<?php echo htmlspecialchars($p['program_name']); ?>">Delete</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_program_id" id="delete_program_id">
                    <p>Are you sure you want to delete <span id="delete_program_name"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this program will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
    var programIdInput = document.getElementById('delete_program_id');
    var programNameSpan = document.getElementById('delete_program_name');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(programId) {
        relatedTablesList.innerHTML = '...';
        fetch('delete.php?action=related&id=' + encodeURIComponent(programId))
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
        var programId = button.getAttribute('data-program-id');
        var programName = button.getAttribute('data-program-name');
        programIdInput.value = programId;
        programNameSpan.textContent = programName;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(programId);
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