<?php
require_once '../../models/Member.php';
$member = new Member();
$members = $member->getAll();

include '../../views/header.php';
?>
<h2>Members</h2>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
<div class="alert alert-success">Member created successfully.</div>
<?php endif; ?>
<a href="create.php" class="btn btn-success mb-3">Add New Member</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>IC</th>
            <th>Contact</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Membership Status</th>
            <th>Membership Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
            <td><?php echo $m['member_id']; ?></td>
            <td><?php echo $m['member_name']; ?></td>
            <td><?php echo $m['member_ic']; ?></td>
            <td><?php echo $m['member_contact']; ?></td>
            <td><?php echo $m['gender']; ?></td>
            <td><?php echo $m['date_of_birth']; ?></td>
            <td><?php echo $m['membership_status']; ?></td>
            <td><?php echo $m['type_name']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $m['member_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-member-id="<?php echo $m['member_id']; ?>" data-member-name="<?php echo htmlspecialchars($m['member_name']); ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </tbody>
    </table>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" id="deleteForm">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Member</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="delete_member_id" id="delete_member_id">
          <p>Are you sure you want to delete <span id="delete_member_name"></span>?</p>
          <div class="alert alert-warning" id="deleteRelatedTables">
            Warning: Deleting this member will also remove all related data from <span id="relatedTablesList"></span>. This action cannot be undone.
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
  var memberIdInput = document.getElementById('delete_member_id');
  var memberNameSpan = document.getElementById('delete_member_name');
  var deleteError = document.getElementById('deleteError');
  var relatedTablesList = document.getElementById('relatedTablesList');
  function fetchRelatedTables(memberId) {
    relatedTablesList.innerHTML = '...';
    fetch('delete.php?action=related&id=' + encodeURIComponent(memberId))
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
    var memberId = button.getAttribute('data-member-id');
    var memberName = button.getAttribute('data-member-name');
    memberIdInput.value = memberId;
    memberNameSpan.textContent = memberName;
    deleteError.classList.add('d-none');
    deleteError.textContent = '';
    fetchRelatedTables(memberId);
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