<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>
  <div class="container">
    <h1 class="mt-2 mb-2">Company Settings</h1>
    <a href="/admin/settings/add" class="btn btn-success mt-2 mb-2">Add New Company</a>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success mt-3" role="alert">
      <?= esc(session()->getFlashdata('success')) ?>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger mt-3" role="alert">
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>
  <?php
  $validation = session()->getFlashdata('validation');
  if ($validation && $validation->getErrors()):
  ?>
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- Desktop / larger screens: Table -->
  <div class="table-responsive d-none d-md-block">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Logo</th>
          <th>Company Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Address</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($companies as $company): ?>
          <tr>
            <td><?= esc($company['id']) ?></td>
            <td class="text-center">
              <?php if (!empty($company['company_logo'])): ?>
                <img src="<?= esc($company['company_logo']) ?>" alt="Logo" class="img-fluid" style="max-height:40px;" />
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td><?= esc($company['company_name']) ?></td>
            <td><?= esc($company['company_phone']) ?></td>
            <td><?= esc($company['company_email']) ?></td>
            <td><?= esc($company['company_address']) ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#companyEditModal"
                data-id="<?= esc($company['id']) ?>" data-company_name="<?= esc($company['company_name']) ?>"
                data-company_phone="<?= esc($company['company_phone']) ?>"
                data-company_email="<?= esc($company['company_email']) ?>"
                data-company_address="<?= esc($company['company_address']) ?>"
                data-company_fssai="<?= esc($company['company_fssai']) ?>"
                data-company_logo="<?= esc($company['company_logo']) ?>"
                data-company_gst="<?= esc($company['company_gst']) ?>"
                data-swiggy="<?= esc($company['swiggy']) ?>"
                data-zomato="<?= esc($company['zomato']) ?>"
                data-whatsapp_group="<?= esc($company['whatsapp_group']) ?>">
                <i class="bi bi-pencil"></i>
              </button>
              <form action="/admin/settings/delete/<?= esc($company['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile / small screens: Cards -->
  <div class="d-block d-md-none">
    <?php foreach ($companies as $company): ?>
      <div class="card mb-3">
        <div class="card-body">
          <?php if (!empty($company['company_logo'])): ?>
            <div class="mb-3 text-center">
              <img src="<?= esc($company['company_logo']) ?>" alt="Logo" class="img-fluid" style="max-height:60px;">
            </div>
          <?php endif; ?>
          <h5 class="card-title mb-1"><?= esc($company['id']) ?> - <?= esc($company['company_name']) ?></h5>
          <p class="card-text mb-1">
            <strong>Phone:</strong> <?= esc($company['company_phone']) ?><br>
            <strong>Email:</strong> <?= esc($company['company_email']) ?><br>
            <strong>Address:</strong> <?= esc($company['company_address']) ?>
          </p>
          <div class="d-flex gap-2">
            <button
              type="button"
              class="btn btn-sm btn-primary me-1"
              data-bs-toggle="modal"
              data-bs-target="#companyEditModal"
              data-id="<?= esc($company['id']) ?>"
              data-company_name="<?= esc($company['company_name']) ?>"
              data-company_phone="<?= esc($company['company_phone']) ?>"
              data-company_email="<?= esc($company['company_email']) ?>"
              data-company_address="<?= esc($company['company_address']) ?>"
              data-company_fssai="<?= esc($company['company_fssai']) ?>"
              data-company_logo="<?= esc($company['company_logo']) ?>"
              data-company_gst="<?= esc($company['company_gst']) ?>"
              data-swiggy="<?= esc($company['swiggy']) ?>"
              data-zomato="<?= esc($company['zomato']) ?>"
              data-whatsapp_group="<?= esc($company['whatsapp_group']) ?>">
              <i class="bi bi-pencil"></i>
            </button>
            <form action="/admin/settings/delete/<?= esc($company['id']) ?>" method="post" class="flex-fill" onsubmit="return confirm('Delete this record?');">
              <?= csrf_field() ?>
              <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<!-- Edit Settings Modal -->
<div class="modal fade" id="companyEditModal" tabindex="-1" aria-labelledby="companyEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="companyEditModalLabel">Edit Company Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="companyEditForm" method="post" action="" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-body">
          <input type="hidden" name="id" id="modal_company_id">
          <input type="hidden" name="existing_company_logo" id="modal_existing_company_logo">

          <div class="mb-3">
            <label for="modal_company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="modal_company_name" name="company_name" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="modal_company_phone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="modal_company_phone" name="company_phone">
            </div>
            <div class="col-md-6 mb-3">
              <label for="modal_company_email" class="form-label">Email</label>
              <input type="email" class="form-control" id="modal_company_email" name="company_email">
            </div>
          </div>

          <div class="mb-3">
            <label for="modal_company_address" class="form-label">Address</label>
            <textarea class="form-control" id="modal_company_address" name="company_address" rows="2"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="modal_company_fssai" class="form-label">FSSAI</label>
              <input type="text" class="form-control" id="modal_company_fssai" name="company_fssai">
            </div>
            <div class="col-md-6 mb-3">
              <label for="modal_company_gst" class="form-label">GST</label>
              <input type="text" class="form-control" id="modal_company_gst" name="company_gst">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="modal_swiggy" class="form-label">Swiggy Link</label>
              <input type="url" class="form-control" id="modal_swiggy" name="swiggy">
            </div>
            <div class="col-md-6 mb-3">
              <label for="modal_zomato" class="form-label">Zomato Link</label>
              <input type="url" class="form-control" id="modal_zomato" name="zomato">
            </div>
          </div>

          <div class="mb-3">
            <label for="modal_whatsapp_group" class="form-label">WhatsApp Group Link</label>
            <input type="url" class="form-control" id="modal_whatsapp_group" name="whatsapp_group">
          </div>

          <div class="mb-3">
            <label for="modal_company_logo" class="form-label">Company Logo</label>
            <input type="file" class="form-control" id="modal_company_logo" name="company_logo" accept="image/*">
            <div class="form-text">Leave blank to keep existing logo (max 2MB).</div>
            <div class="mt-2" id="modal_logo_preview"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('companyEditModal');
    if (!editModal) return;

    editModal.addEventListener('show.bs.modal', function(event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      if (!id) return;

      var form = document.getElementById('companyEditForm');
      form.action = '/admin/settings/update/' + id;
      document.getElementById('modal_company_id').value = id;

      document.getElementById('modal_company_name').value = button.getAttribute('data-company_name') || '';
      document.getElementById('modal_company_phone').value = button.getAttribute('data-company_phone') || '';
      document.getElementById('modal_company_email').value = button.getAttribute('data-company_email') || '';
      document.getElementById('modal_company_address').value = button.getAttribute('data-company_address') || '';
      document.getElementById('modal_company_fssai').value = button.getAttribute('data-company_fssai') || '';
      document.getElementById('modal_company_gst').value = button.getAttribute('data-company_gst') || '';
      document.getElementById('modal_swiggy').value = button.getAttribute('data-swiggy') || '';
      document.getElementById('modal_zomato').value = button.getAttribute('data-zomato') || '';
      document.getElementById('modal_whatsapp_group').value = button.getAttribute('data-whatsapp_group') || '';
      var logoPath = button.getAttribute('data-company_logo') || '';
      document.getElementById('modal_company_logo').value = '';
      document.getElementById('modal_existing_company_logo').value = logoPath;

      var preview = document.getElementById('modal_logo_preview');
      if (logoPath) {
        preview.innerHTML = '<img src="' + logoPath + '" alt="Logo" class="img-fluid" style="max-height:80px;">';
      } else {
        preview.innerHTML = '<span class="text-muted">No logo uploaded</span>';
      }
    });
  });
</script>

<?= view('/partials/adminfooter') ?>