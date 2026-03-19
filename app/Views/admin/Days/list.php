<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Days</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dayModal" id="addDayBtn">
      <i class="bi bi-plus-lg"></i> Add Day
    </button>
  </div>


  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 10%">S.No</th>
          <th>Day</th>
          <th class="text-center" style="width: 20%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($days)): ?>
          <?php foreach ($days as $day): ?>
            <tr>
              <td><?= esc($day['id']) ?></td>
              <td><?= esc($day['day_name']) ?></td>
              <td class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-sm btn-primary me-1 edit-day-btn"
                  data-id="<?= esc($day['id']) ?>"
                  data-day_name="<?= esc($day['day_name']) ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#dayModal">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="/admin/days/delete/<?= esc($day['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this day?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No days found. Add a new day to get started.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Day Modal -->
<div class="modal fade" id="dayModal" tabindex="-1" aria-labelledby="dayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="dayForm" method="post" action="/admin/days/create">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="dayModalLabel">Add Day</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="day_id" value="">
          <div class="mb-3">
            <label for="day_name" class="form-label">Day Name</label>
            <input type="text" class="form-control" id="day_name" name="day_name" required maxlength="50" placeholder="e.g. Monday">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="daySubmitBtn">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function() {
    var modalEl = document.getElementById('dayModal');
    var dayForm = document.getElementById('dayForm');
    var modalTitle = document.getElementById('dayModalLabel');
    var submitBtn = document.getElementById('daySubmitBtn');
    var dayIdInput = document.getElementById('day_id');
    var dayNameInput = document.getElementById('day_name');

    var editButtons = document.querySelectorAll('.edit-day-btn');

    editButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        var id = this.dataset.id;
        var dayName = this.dataset.day_name;

        modalTitle.textContent = 'Edit Day';
        submitBtn.textContent = 'Update';
        dayIdInput.value = id;
        dayNameInput.value = dayName;
        dayForm.action = '/admin/days/update/' + id;
      });
    });

    document.getElementById('addDayBtn').addEventListener('click', function() {
      modalTitle.textContent = 'Add Day';
      submitBtn.textContent = 'Create';
      dayIdInput.value = '';
      dayNameInput.value = '';
      dayForm.action = '/admin/days/create';
    });

    // Reset form when modal closes
    modalEl.addEventListener('hidden.bs.modal', function() {
      dayForm.reset();
      dayIdInput.value = '';
      dayForm.action = '/admin/days/create';
      modalTitle.textContent = 'Add Day';
      submitBtn.textContent = 'Create';
    });
  })();
</script>

<?= view('/partials/adminfooter') ?>