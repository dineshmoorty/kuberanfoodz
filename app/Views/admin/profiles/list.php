<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<?php
$modalMode = $modalMode ?? '';
$modalProfileId = $modalProfileId ?? 0;
$modalProfileRoleSlug = $modalProfileRoleSlug ?? '';
$modalProfileRoleName = $modalProfileRoleName ?? '';
$currentAdminId = $currentAdminId ?? 0;
$showPasswordSection = !empty($showPasswordSection);
$roles = $roles ?? [];
$companies = $companies ?? [];
$roleMap = [];
$companyMap = [];

foreach ($roles as $role) {
  $roleMap[$role['id']] = $role;
}

foreach ($companies as $company) {
  $companyMap[$company['id']] = $company;
}
?>

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Profiles</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#profileModal" id="addProfileBtn">
      <i class="bi bi-plus-lg"></i> Add Profile
    </button>
  </div>

  <div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 7%">ID</th>
          <th>Username</th>
          <th>Name</th>
          <th>Company</th>
          <th>Role</th>
          <th>Mobile</th>
          <th>DOB</th>
          <th class="text-center" style="width: 16%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($profiles)): ?>
          <?php foreach ($profiles as $profile): ?>
            <?php $role = $roleMap[$profile['role_id'] ?? 0] ?? null; ?>
            <?php $company = $companyMap[$profile['company_id'] ?? 0] ?? null; ?>
            <?php $isAdminProfile = ($role['slug'] ?? ($profile['role'] ?? '')) === 'admin'; ?>
            <tr>
              <td><?= esc($profile['id']) ?></td>
              <td><?= esc($profile['username']) ?></td>
              <td><?= esc(($profile['name'] ?? '') ?: 'Not set') ?></td>
              <td><?= esc($company['company_name'] ?? 'Not linked') ?></td>
              <td><?= esc($role['name'] ?? 'Not linked') ?></td>
              <td><?= esc(($profile['mobile'] ?? '') ?: 'Not set') ?></td>
              <td><?= esc(($profile['dob'] ?? '') ?: 'Not set') ?></td>
              <td class="d-flex justify-content-center gap-2">
                <button
                  type="button"
                  class="btn btn-sm btn-primary edit-profile-btn"
                  data-id="<?= esc($profile['id']) ?>"
                  data-username="<?= esc($profile['username']) ?>"
                  data-name="<?= esc($profile['name'] ?? '') ?>"
                  data-dob="<?= esc($profile['dob'] ?? '') ?>"
                  data-mobile="<?= esc($profile['mobile'] ?? '') ?>"
                  data-role_id="<?= esc($profile['role_id'] ?? '') ?>"
                  data-role_name="<?= esc($role['name'] ?? 'Admin') ?>"
                  data-role_slug="<?= esc($role['slug'] ?? ($profile['role'] ?? '')) ?>"
                  data-company_id="<?= esc($profile['company_id'] ?? '') ?>"
                  data-is-self="<?= (int) $profile['id'] === (int) $currentAdminId ? '1' : '0' ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#profileModal">
                  <i class="bi bi-pencil"></i>
                </button>
                <?php if (!$isAdminProfile): ?>
                  <form action="/admin/profiles/delete/<?= esc($profile['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this profile?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">No profiles found. Add a profile to get started.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-block d-md-none">
    <?php if (!empty($profiles)): ?>
      <?php foreach ($profiles as $profile): ?>
        <?php $role = $roleMap[$profile['role_id'] ?? 0] ?? null; ?>
        <?php $company = $companyMap[$profile['company_id'] ?? 0] ?? null; ?>
        <?php $isAdminProfile = ($role['slug'] ?? ($profile['role'] ?? '')) === 'admin'; ?>
        <div class="card mb-3 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
              <div>
                <h5 class="card-title mb-1"><?= esc(($profile['name'] ?? '') ?: 'Not set') ?></h5>

              </div>
              <span class="badge text-bg-secondary"><?= esc($role['name'] ?? 'Not linked') ?></span>
            </div>

            <div class="small">
              <p class="mb-1"><strong>Company:</strong> <?= esc($company['company_name'] ?? 'Not linked') ?></p>
              <p class="mb-1"><strong>Mobile:</strong> <?= esc(($profile['mobile'] ?? '') ?: 'Not set') ?></p>
              <p class="mb-0"><strong>DOB:</strong> <?= esc(($profile['dob'] ?? '') ?: 'Not set') ?></p>
            </div>

            <div class="d-flex gap-2 mt-3">
              <button
                type="button"
                class="btn btn-sm btn-primary edit-profile-btn"
                data-id="<?= esc($profile['id']) ?>"
                data-username="<?= esc($profile['username']) ?>"
                data-name="<?= esc($profile['name'] ?? '') ?>"
                data-dob="<?= esc($profile['dob'] ?? '') ?>"
                data-mobile="<?= esc($profile['mobile'] ?? '') ?>"
                data-role_id="<?= esc($profile['role_id'] ?? '') ?>"
                data-role_name="<?= esc($role['name'] ?? 'Admin') ?>"
                data-role_slug="<?= esc($role['slug'] ?? ($profile['role'] ?? '')) ?>"
                data-company_id="<?= esc($profile['company_id'] ?? '') ?>"
                data-is-self="<?= (int) $profile['id'] === (int) $currentAdminId ? '1' : '0' ?>"
                data-bs-toggle="modal"
                data-bs-target="#profileModal">
                <i class="bi bi-pencil"></i>
              </button>
              <?php if (!$isAdminProfile): ?>
                <form action="/admin/profiles/delete/<?= esc($profile['id']) ?>" method="post" class="flex-fill" onsubmit="return confirm('Delete this profile?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger "><i class="bi bi-trash"></i></button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info mb-0" role="alert">
        No profiles found. Add a profile to get started.
      </div>
    <?php endif; ?>
  </div>

  <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="d-flex justify-content-center mt-3">
      <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="profileForm" method="post" action="/admin/profiles/create">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Add Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="profile_is_self" value="0">

          <div class="row g-3">
            <div class="col-md-6">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>" required>
            </div>

            <div class="col-md-4">
              <label for="company_id" class="form-label">Company</label>
              <select class="form-select" id="company_id" name="company_id" required>
                <option value="">Select company</option>
                <?php foreach ($companies as $company): ?>
                  <option value="<?= esc($company['id']) ?>" <?= old('company_id') == $company['id'] ? 'selected' : '' ?>><?= esc($company['company_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="role_id" class="form-label">Role</label>
              <select class="form-select" id="role_id" name="role_id" >
                <option value="">Select role</option>
                <?php foreach ($roles as $role): ?>
                  <?php if (($role['slug'] ?? '') === 'admin') continue; ?>
                  <option value="<?= esc($role['id']) ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>><?= esc($role['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="mobile" class="form-label">Mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile" maxlength="10" inputmode="numeric" value="<?= esc(old('mobile')) ?>">
            </div>

            <div class="col-md-6">
              <label for="dob" class="form-label">DOB</label>
              <input type="date" class="form-control" id="dob" name="dob" value="<?= esc(old('dob')) ?>">
            </div>
          </div>

          <hr class="my-4">

          <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0" id="passwordSectionTitle">Set Password</h6>
            <small class="text-muted" id="passwordSectionHint">Password is required for new profiles.</small>
          </div>

          <div class="row g-3">
            <div class="col-md-4 d-none" id="currentPasswordWrapper">
              <label for="current_password" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="current_password" name="current_password">
            </div>

            <div class="col-md-4">
              <label for="new_password" class="form-label" id="newPasswordLabel">Password</label>
              <input type="password" class="form-control" id="new_password" name="new_password">
            </div>

            <div class="col-md-4">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
          </div>

          <div class="alert alert-warning mt-3 mb-0 d-none" id="selfPasswordNotice">
            If you change your own password, you will be logged out and need to login again.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="profileSubmitBtn">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function() {
    var modalEl = document.getElementById('profileModal');
    var form = document.getElementById('profileForm');
    var title = document.getElementById('profileModalLabel');
    var submitBtn = document.getElementById('profileSubmitBtn');
    var addBtn = document.getElementById('addProfileBtn');
    var currentPasswordWrapper = document.getElementById('currentPasswordWrapper');
    var currentPasswordInput = document.getElementById('current_password');
    var selfPasswordNotice = document.getElementById('selfPasswordNotice');
    var passwordSectionTitle = document.getElementById('passwordSectionTitle');
    var passwordSectionHint = document.getElementById('passwordSectionHint');
    var newPasswordLabel = document.getElementById('newPasswordLabel');
    var profileIsSelfInput = document.getElementById('profile_is_self');
    var usernameInput = document.getElementById('username');
    var nameInput = document.getElementById('name');
    var dobInput = document.getElementById('dob');
    var mobileInput = document.getElementById('mobile');
    var companyInput = document.getElementById('company_id');
    var roleInput = document.getElementById('role_id');
    var newPasswordInput = document.getElementById('new_password');
    var confirmPasswordInput = document.getElementById('confirm_password');
    var baseRoleOptions = roleInput.innerHTML;

    function toggleSelfPasswordRequirement() {
      var isSelf = profileIsSelfInput.value === '1';
      var hasPassword = newPasswordInput.value.length > 0 || confirmPasswordInput.value.length > 0;

      if (!isSelf) {
        currentPasswordWrapper.classList.add('d-none');
        currentPasswordInput.required = false;
        return;
      }

      currentPasswordWrapper.classList.toggle('d-none', !hasPassword);
      currentPasswordInput.required = hasPassword;
    }

    function resetPasswordSection() {
      currentPasswordWrapper.classList.add('d-none');
      currentPasswordInput.required = false;
      currentPasswordInput.value = '';
      selfPasswordNotice.classList.add('d-none');
      passwordSectionTitle.textContent = 'Set Password';
      passwordSectionHint.textContent = 'Password is required for new profiles.';
      newPasswordLabel.textContent = 'Password';
    }

    function fillCreateMode(useOldInput) {
      title.textContent = 'Add Profile';
      submitBtn.textContent = 'Create';
      form.action = '/admin/profiles/create';
      profileIsSelfInput.value = '0';
      roleInput.innerHTML = baseRoleOptions;
      resetPasswordSection();

      if (!useOldInput) {
        form.reset();
      }

      newPasswordInput.required = true;
      confirmPasswordInput.required = true;
    }

    function fillEditMode(profile) {
      title.textContent = 'Edit Profile';
      submitBtn.textContent = 'Update';
      form.action = '/admin/profiles/update/' + profile.id;
      profileIsSelfInput.value = profile.isSelf;
      usernameInput.value = profile.username || '';
      nameInput.value = profile.name || '';
      dobInput.value = profile.dob || '';
      mobileInput.value = profile.mobile || '';
      companyInput.value = profile.companyId || '';
      roleInput.innerHTML = baseRoleOptions;
      roleInput.value = profile.roleId || '';
      newPasswordInput.value = '';
      confirmPasswordInput.value = '';
      newPasswordInput.required = false;
      confirmPasswordInput.required = false;
      resetPasswordSection();

      passwordSectionTitle.textContent = 'Change Password';
      passwordSectionHint.textContent = 'Leave password fields empty if you do not want to change it.';
      newPasswordLabel.textContent = 'New Password';

      if (profile.isSelf === '1') {
        selfPasswordNotice.classList.remove('d-none');
      }

      if (profile.roleSlug === 'admin') {
        roleInput.innerHTML = '<option value="' + profile.roleId + '" selected>' + (profile.roleName || 'Admin') + ' (Locked)</option>';
      }

      if (profile.isSelf === '1' && profile.showPassword === '1') {
        currentPasswordWrapper.classList.remove('d-none');
        currentPasswordInput.required = true;
      }
    }

    addBtn.addEventListener('click', function() {
      fillCreateMode(false);
    });

    document.querySelectorAll('.edit-profile-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        fillEditMode({
          id: this.dataset.id,
          username: this.dataset.username,
          name: this.dataset.name,
          dob: this.dataset.dob,
          mobile: this.dataset.mobile,
          companyId: this.dataset.company_id,
          roleId: this.dataset.role_id,
          roleName: this.dataset.role_name,
          roleSlug: this.dataset.role_slug,
          isSelf: this.dataset.isSelf,
          showPassword: '0'
        });
      });
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
      fillCreateMode(false);
    });

    newPasswordInput.addEventListener('input', toggleSelfPasswordRequirement);
    confirmPasswordInput.addEventListener('input', toggleSelfPasswordRequirement);

    <?php if ($modalMode === 'create'): ?>
      fillCreateMode(true);
      new bootstrap.Modal(modalEl).show();
    <?php elseif ($modalMode === 'edit'): ?>
      fillEditMode({
        id: '<?= esc((string) $modalProfileId) ?>',
        username: '<?= esc((string) old('username')) ?>',
        name: '<?= esc((string) old('name')) ?>',
        dob: '<?= esc((string) old('dob')) ?>',
        mobile: '<?= esc((string) old('mobile')) ?>',
        companyId: '<?= esc((string) old('company_id')) ?>',
        roleId: '<?= esc((string) old('role_id')) ?>',
        roleName: '<?= esc((string) $modalProfileRoleName) ?>',
        roleSlug: '<?= esc((string) $modalProfileRoleSlug) ?>',
        isSelf: '<?= $modalProfileId === $currentAdminId ? '1' : '0' ?>',
        showPassword: '<?= $showPasswordSection ? '1' : '0' ?>'
      });
      new bootstrap.Modal(modalEl).show();
    <?php else: ?>
      fillCreateMode(false);
    <?php endif; ?>
  })();
</script>

<?= view('/partials/adminfooter') ?>
