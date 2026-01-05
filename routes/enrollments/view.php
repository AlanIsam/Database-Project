<?php
require_once '../../models/Enrollment.php';
$enrollment = new Enrollment();
$enrollments = $enrollment->getAll();

include '../../views/header.php';
?>
<h2>Enrollments</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Enrollment</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Member</th>
            <th>Class Date</th>
            <th>Class Time</th>
            <th>Program</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($enrollments as $e): ?>
        <tr>
            <td><?php echo $e['member_name']; ?></td>
            <td><?php echo $e['class_date']; ?></td>
            <td><?php echo $e['start_time'] . ' - ' . $e['end_time']; ?></td>
            <td><?php echo $e['program_name']; ?></td>
            <td>
                <a href="edit.php?class_id=<?php echo $e['class_id']; ?>&member_id=<?php echo $e['member_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-class-id="<?php echo $e['class_id']; ?>" data-member-id="<?php echo $e['member_id']; ?>" data-label="<?php echo htmlspecialchars($e['member_name'] . ' / ' . $e['program_name']); ?>">Delete</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="class_id" id="delete_class_id">
                    <input type="hidden" name="member_id" id="delete_member_id">
                    <p>Are you sure you want to delete <span id="delete_label"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this enrollment will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
    var classIdInput = document.getElementById('delete_class_id');
    var memberIdInput = document.getElementById('delete_member_id');
    var deleteLabelSpan = document.getElementById('delete_label');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(classId, memberId) {
        relatedTablesList.innerHTML = '...';
        var url = 'delete.php?action=related&class_id=' + encodeURIComponent(classId) + '&member_id=' + encodeURIComponent(memberId);
        fetch(url)
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
        var classId = button.getAttribute('data-class-id');
        var memberId = button.getAttribute('data-member-id');
        var label = button.getAttribute('data-label');
        classIdInput.value = classId;
        memberIdInput.value = memberId;
        deleteLabelSpan.textContent = label;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(classId, memberId);
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