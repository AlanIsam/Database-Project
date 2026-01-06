<?php
require_once '../../models/ClassModel.php';
$classModel = new ClassModel();
$classes = $classModel->getAll();

include '../../views/header.php';
?>
<h2>Classes</h2>
<a href="create.php" class="btn btn-success mb-3">Add New Class</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Program</th>
            <th>Trainer</th>
            <th>Category</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Room</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classes as $c): ?>
        <tr>
            <td><?php echo $c['class_id']; ?></td>
            <td><?php echo $c['program_name']; ?></td>
            <td><?php echo $c['trainer_name']; ?></td>
            <td><?php echo $c['category_name']; ?></td>
            <td><?php echo $c['class_date']; ?></td>
            <td><?php echo $c['start_time']; ?></td>
            <td><?php echo $c['end_time']; ?></td>
            <td><?php echo $c['room_number']; ?></td>
            <td>
            <?php 
            $status = $c['dynamic_status'];

            $badgeClass = 'bg-secondary'; 

            if ($status === 'Active') {
                $badgeClass = 'bg-success'; // Green
            } elseif ($status === 'Cancelled') {
                $badgeClass = 'bg-danger'; // Red
            } elseif ($status === 'Completed') {
                $badgeClass = 'bg-primary'; // Blue
            }
            ?>
            <span class="badge <?php echo $badgeClass; ?>">
                <?php echo $status; ?>
            </span>
        </td>
            <td>
                <a href="edit.php?id=<?php echo $c['class_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-class-id="<?php echo $c['class_id']; ?>" data-class-name="<?php echo htmlspecialchars($c['program_name']); ?>">Delete</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_class_id" id="delete_class_id">
                    <p>Are you sure you want to delete <span id="delete_class_name"></span>?</p>
                    <div class="alert alert-warning" id="deleteRelatedTables">
                        Warning: Deleting this class will also impact related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
    var classNameSpan = document.getElementById('delete_class_name');
    var deleteError = document.getElementById('deleteError');
    var relatedTablesList = document.getElementById('relatedTablesList');

    function fetchRelatedTables(classId) {
        relatedTablesList.innerHTML = '...';
        fetch('delete.php?action=related&id=' + encodeURIComponent(classId))
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
        var className = button.getAttribute('data-class-name');
        classIdInput.value = classId;
        classNameSpan.textContent = className;
        deleteError.classList.add('d-none');
        deleteError.textContent = '';
        fetchRelatedTables(classId);
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
