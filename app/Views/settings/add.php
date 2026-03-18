<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h1>Add Company Settings</h1>
      <a href="/admin/settings" class="btn btn-secondary">Back to List</a>
    </div>
  </div>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger mt-3" role="alert">
      <?= esc(session()->getFlashdata('error')) ?>
    </div>
  <?php endif; ?>

  <?php if (isset($validation) && $validation->getErrors()): ?>
    <div class="alert alert-danger mt-3">
      <ul class="mb-0">
        <?php foreach ($validation->getErrors() as $error): ?>
          <li><?= esc($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-12 col-lg-8">
      <div class="card mt-3">
        <div class="card-body">
          <form action="/admin/settings/create" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="mb-3">
              <label for="company_name" class="form-label">Company Name</label>
              <input type="text" class="form-control" id="company_name" name="company_name" value="<?= esc(old('company_name')) ?>" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="company_phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="company_phone" name="company_phone" value="<?= esc(old('company_phone')) ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="company_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="company_email" name="company_email" value="<?= esc(old('company_email')) ?>" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="company_address" class="form-label">Address</label>
              <textarea class="form-control" id="company_address" name="company_address" rows="2"><?= esc(old('company_address')) ?></textarea>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="company_fssai" class="form-label">FSSAI</label>
                <input type="text" class="form-control" id="company_fssai" name="company_fssai" value="<?= esc(old('company_fssai')) ?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="company_gst" class="form-label">GST</label>
                <input type="text" class="form-control" id="company_gst" name="company_gst" value="<?= esc(old('company_gst')) ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="swiggy" class="form-label">Swiggy Link</label>
                <input type="url" class="form-control" id="swiggy" name="swiggy" value="<?= esc(old('swiggy')) ?>">
              </div>
              <div class="col-md-6 mb-3">
                <label for="zomato" class="form-label">Zomato Link</label>
                <input type="url" class="form-control" id="zomato" name="zomato" value="<?= esc(old('zomato')) ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="whatsapp_group" class="form-label">WhatsApp Group Link</label>
              <input type="url" class="form-control" id="whatsapp_group" name="whatsapp_group" value="<?= esc(old('whatsapp_group')) ?>">
            </div>

            <div class="mb-3">
              <label for="company_logo" class="form-label">Company Logo</label>
              <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
              <div class="form-text">Accepted formats: jpg/jpeg/png/svg (max 2MB).</div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Create</button>
              <a href="/admin/settings" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= view('/partials/adminfooter') ?>