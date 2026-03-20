<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<?php
$modalMode = $modalMode ?? '';
$modalRoleId = $modalRoleId ?? 0;
?>

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Roles</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#roleModal" id="addRoleBtn">
      <i class="bi bi-plus-lg"></i> Add Role
    </button>
  </div>

  <div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 8%">ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Description</th>
          <th class="text-center" style="width: 18%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($roles)): ?>
          <?php foreach ($roles as $role): ?>
            <tr>
              <td><?= esc($role['id']) ?></td>
              <td><?= esc($role['name']) ?></td>
              <td><span class="badge text-bg-secondary"><?= esc($role['slug']) ?></span></td>
              <td><?= esc($role['description'] ?: 'No description') ?></td>
              <td class="d-flex justify-content-center gap-2">
                <button
                  type="button"
                  class="btn btn-sm btn-primary edit-role-btn"
                  data-id="<?= esc($role['id']) ?>"
                  data-name="<?= esc($role['name']) ?>"
                  data-description="<?= esc($role['description'] ?? '') ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#roleModal">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="/admin/roles/delete/<?= esc($role['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this role?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">No roles found. Add a role to get started.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-block d-md-none">
    <?php if (!empty($roles)): ?>
      <?php foreach ($roles as $role): ?>
        <div class="card mb-3 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
              <div>
                <h5 class="card-title mb-1"><?= esc($role['name']) ?></h5>
              </div>
              <span class="badge text-bg-secondary"><?= esc($role['slug']) ?></span>
            </div>

            <p class="small mb-3"><strong>Description:</strong> <?= esc($role['description'] ?: 'No description') ?></p>

            <div class="d-flex gap-2">
              <button
                type="button"
                class="btn btn-sm btn-primary edit-role-btn"
                data-id="<?= esc($role['id']) ?>"
                data-name="<?= esc($role['name']) ?>"
                data-description="<?= esc($role['description'] ?? '') ?>"
                data-bs-toggle="modal"
                data-bs-target="#roleModal">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="/admin/roles/delete/<?= esc($role['id']) ?>" method="post" class="flex-fill" onsubmit="return confirm('Delete this role?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info mb-0" role="alert">
        No roles found. Add a role to get started.
      </div>
    <?php endif; ?>
  </div>

  <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="d-flex justify-content-end mt-3">
      <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="roleForm" method="post" action="/admin/roles/create">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="role_name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="role_name" name="name" value="<?= esc(old('name')) ?>" required>
          </div>
          <div class="mb-0">
            <label for="role_description" class="form-label">Description</label>
            <textarea class="form-control" id="role_description" name="description" rows="3" placeholder="Optional short description"><?= esc(old('description')) ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="roleSubmitBtn">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function() {
    var modalEl = document.getElementById('roleModal');
    var form = document.getElementById('roleForm');
    var title = document.getElementById('roleModalLabel');
    var submitBtn = document.getElementById('roleSubmitBtn');
    var nameInput = document.getElementById('role_name');
    var descriptionInput = document.getElementById('role_description');

    document.getElementById('addRoleBtn').addEventListener('click', function() {
      title.textContent = 'Add Role';
      submitBtn.textContent = 'Create';
      form.action = '/admin/roles/create';
      form.reset();
    });

    document.querySelectorAll('.edit-role-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        title.textContent = 'Edit Role';
        submitBtn.textContent = 'Update';
        form.action = '/admin/roles/update/' + this.dataset.id;
        nameInput.value = this.dataset.name || '';
        descriptionInput.value = this.dataset.description || '';
      });
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
      title.textContent = 'Add Role';
      submitBtn.textContent = 'Create';
      form.action = '/admin/roles/create';
      form.reset();
    });

    <?php if ($modalMode === 'create'): ?>
      new bootstrap.Modal(modalEl).show();
    <?php elseif ($modalMode === 'edit'): ?>
      title.textContent = 'Edit Role';
      submitBtn.textContent = 'Update';
      form.action = '/admin/roles/update/<?= esc((string) $modalRoleId) ?>';
      new bootstrap.Modal(modalEl).show();
    <?php endif; ?>
  })();
</script>

<?= view('/partials/adminfooter') ?>
