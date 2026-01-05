<?php
require_once '../../models/Employee.php';
$employee = new Employee();
$employees = $employee->getAll();
$cashiers = array_filter($employees, function($e) { return $e['employee_type'] === 'C'; });
$trainers = array_filter($employees, function($e) { return $e['employee_type'] === 'T'; });
include '../../views/header.php';
?>
<h2>Employees</h2>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div class="alert alert-success">Employee created successfully.</div>
<?php endif; ?>
<a href="create.php" class="btn btn-success mb-3">Add New Employee</a>

<h3>Cashiers</h3>
<div class="table-responsive">
<table class="table table-striped align-middle text-wrap" style="word-break:break-word; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width: 5%">ID</th>
            <th style="width: 15%">Name</th>
            <th style="width: 13%">IC</th>
            <th style="width: 13%">Contact</th>
            <th style="width: 8%">Gender</th>
            <th style="width: 12%">Date of Birth</th>
            <th style="width: 12%">Date Working</th>
            <th style="width: 10%">Salary</th>
            <th style="width: 12%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cashiers as $e): ?>
        <tr>
            <td><?php echo $e['employee_id']; ?></td>
            <td><?php echo $e['employee_name']; ?></td>
            <td><?php echo $e['employee_ic']; ?></td>
            <td><?php echo $e['employee_contact']; ?></td>
            <td><?php echo $e['gender']; ?></td>
            <td><?php echo $e['date_of_birth']; ?></td>
            <td><?php echo $e['date_working']; ?></td>
            <td><?php echo $e['employee_salary']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $e['employee_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-employee-id="<?php echo $e['employee_id']; ?>" data-employee-name="<?php echo htmlspecialchars($e['employee_name']); ?>">Delete</button>
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
                        <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_employee_id" id="delete_employee_id">
                        <p>Are you sure you want to delete <span id="delete_employee_name"></span>?</p>
                        <div class="alert alert-warning" id="deleteRelatedTables">
                            Warning: Deleting this employee will also remove all related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
</div>

<h3>Trainers</h3>
<div class="table-responsive">
<table class="table table-striped align-middle text-wrap" style="word-break:break-word; table-layout:fixed;">
    <thead>
        <tr>
            <th style="width: 5%">ID</th>
            <th style="width: 15%">Name</th>
            <th style="width: 13%">IC</th>
            <th style="width: 13%">Contact</th>
            <th style="width: 8%">Gender</th>
            <th style="width: 12%">Date of Birth</th>
            <th style="width: 12%">Date Working</th>
            <th style="width: 10%">Salary</th>
            <th style="width: 12%">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($trainers as $e): ?>
        <tr>
            <td><?php echo $e['employee_id']; ?></td>
            <td><?php echo $e['employee_name']; ?></td>
            <td><?php echo $e['employee_ic']; ?></td>
            <td><?php echo $e['employee_contact']; ?></td>
            <td><?php echo $e['gender']; ?></td>
            <td><?php echo $e['date_of_birth']; ?></td>
            <td><?php echo $e['date_working']; ?></td>
            <td><?php echo $e['employee_salary']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $e['employee_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-employee-id="<?php echo $e['employee_id']; ?>" data-employee-name="<?php echo htmlspecialchars($e['employee_name']); ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('deleteModal');
        var deleteForm = document.getElementById('deleteForm');
        var employeeIdInput = document.getElementById('delete_employee_id');
        var employeeNameSpan = document.getElementById('delete_employee_name');
        var deleteError = document.getElementById('deleteError');
        var relatedTablesList = document.getElementById('relatedTablesList');

        function fetchRelatedTables(employeeId) {
            relatedTablesList.innerHTML = '...';
            fetch('delete.php?action=related&id=' + encodeURIComponent(employeeId))
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
            var employeeId = button.getAttribute('data-employee-id');
            var employeeName = button.getAttribute('data-employee-name');
            employeeIdInput.value = employeeId;
            employeeNameSpan.textContent = employeeName;
            deleteError.classList.add('d-none');
            deleteError.textContent = '';
            fetchRelatedTables(employeeId);
        });
        deleteForm.onsubmit = function(e) {
            e.preventDefault();
            var formData = new FormData(deleteForm);
            fetch('delete.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    deleteError.innerHTML = data.error;
                    deleteError.classList.remove('d-none');
                }
            })
            .catch(() => {
                deleteError.textContent = 'An error occurred.';
                deleteError.classList.remove('d-none');
            });
        };
    });
</script>
    
<?php include '../../views/footer.php'; ?>
