<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<?php
$modalMode = $modalMode ?? '';
$modalDishId = $modalDishId ?? 0;
$days = $days ?? [];
$categories = $categories ?? [];
$dishes = $dishes ?? [];
$dishDayMap = $dishDayMap ?? [];
$oldDayIds = old('day_ids');

if (!is_array($oldDayIds)) {
  $oldDayIds = [];
}

$dayMap = [];
$categoryMap = [];

foreach ($days as $day) {
  $dayMap[(int) $day['id']] = $day;
}

foreach ($categories as $category) {
  $categoryMap[(int) $category['id']] = $category;
}

$resolveDayNames = static function(array $selectedDayIds) use ($dayMap): array {
  $names = [];

  foreach ($selectedDayIds as $selectedDayId) {
    $selectedDayId = (int) $selectedDayId;

    if (isset($dayMap[$selectedDayId])) {
      $names[] = $dayMap[$selectedDayId]['day_name'];
    }
  }

  return $names;
};

$resolveImageUrl = static function(?string $path): string {
  $path = trim((string) $path);

  if ($path === '') {
    return '';
  }

  if (preg_match('#^https?://#i', $path)) {
    return $path;
  }

  return base_url(ltrim($path, '/'));
};
?>

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Dishes</h1>
    <button type="button" class="btn btn-success" id="addDishBtn" data-bs-toggle="modal" data-bs-target="#dishModal">
      <i class="bi bi-plus-lg"></i> Add Dish
    </button>
  </div>

  <div class="table-responsive d-none d-lg-block">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 6%">S.No</th>
          <th style="width: 10%">Image</th>
          <th>Dish</th>
          <th>Category</th>
          <th>Days</th>
          <th>Price</th>
          <th>MRP</th>
          <th>Status</th>
          <th class="text-center" style="width: 16%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($dishes)): ?>
          <?php foreach ($dishes as $dish): ?>
            <?php
            $dishId = (int) ($dish['id'] ?? 0);
            $category = $categoryMap[(int) ($dish['category_id'] ?? 0)] ?? null;
            $selectedDayIds = $dishDayMap[$dishId] ?? [];
            $selectedDayNames = $resolveDayNames($selectedDayIds);
            $dishImageUrl = $resolveImageUrl($dish['dish_image'] ?? '');
            $dishThumbnailUrl = $resolveImageUrl($dish['dish_thumbnails'] ?? '');
            ?>
            <tr>
              <td><?= esc($dishId) ?></td>
              <td>
                <?php if ($dishImageUrl !== ''): ?>
                  <img src="<?= esc($dishImageUrl) ?>" alt="<?= esc($dish['dish_name']) ?>" class="img-thumbnail" style="width: 56px; height: 56px; object-fit: cover;" loading="lazy" decoding="async">
                <?php else: ?>
                  <span class="text-muted">No image</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="fw-semibold"><?= esc($dish['dish_name']) ?></div>
                <div class="small text-muted text-truncate" style="max-width: 220px;"><?= esc(($dish['dish_desc'] ?? '') ?: 'No description') ?></div>
              </td>
              <td><?= esc($category['category_name'] ?? 'Not linked') ?></td>
              <td>
                <?php if ((int) ($dish['is_daily'] ?? 0) === 1): ?>
                  <span class="badge text-bg-success">Daily</span>
                <?php elseif (!empty($selectedDayNames)): ?>
                  <?= esc(implode(', ', $selectedDayNames)) ?>
                <?php else: ?>
                  <span class="text-muted">Not linked</span>
                <?php endif; ?>
              </td>
              <td>Rs. <?= esc(number_format((float) ($dish['dish_price'] ?? 0), 2)) ?></td>
              <td>Rs. <?= esc(number_format((float) ($dish['dish_mrp'] ?? 0), 2)) ?></td>
              <td>
                <?php if ((int) ($dish['status'] ?? 0) === 1): ?>
                  <span class="badge text-bg-success">Active</span>
                <?php else: ?>
                  <span class="badge text-bg-danger">Inactive</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-sm btn-primary edit-dish-btn"
                  data-id="<?= esc($dishId) ?>"
                  data-dish_name="<?= esc($dish['dish_name']) ?>"
                  data-dish_price="<?= esc($dish['dish_price']) ?>"
                  data-dish_mrp="<?= esc($dish['dish_mrp']) ?>"
                  data-dish_desc="<?= esc($dish['dish_desc'] ?? '') ?>"
                  data-day_ids="<?= esc(implode(',', $selectedDayIds)) ?>"
                  data-category_id="<?= esc($dish['category_id']) ?>"
                  data-is_daily="<?= esc($dish['is_daily'] ?? 0) ?>"
                  data-status="<?= esc($dish['status'] ?? 1) ?>"
                  data-dish_image="<?= esc($dishImageUrl) ?>"
                  data-dish_thumbnails="<?= esc($dishThumbnailUrl) ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#dishModal">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="/admin/dishes/delete/<?= esc($dishId) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this dish?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="text-center">No dishes found. Add a dish to get started.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-block d-lg-none">
    <?php if (!empty($dishes)): ?>
      <?php foreach ($dishes as $dish): ?>
        <?php
        $dishId = (int) ($dish['id'] ?? 0);
        $category = $categoryMap[(int) ($dish['category_id'] ?? 0)] ?? null;
        $selectedDayIds = $dishDayMap[$dishId] ?? [];
        $selectedDayNames = $resolveDayNames($selectedDayIds);
        $dishImageUrl = $resolveImageUrl($dish['dish_image'] ?? '');
        $dishThumbnailUrl = $resolveImageUrl($dish['dish_thumbnails'] ?? '');
        ?>
        <div class="card mb-3 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex gap-3 align-items-start">
              <div class="flex-shrink-0">
                <?php if ($dishImageUrl !== ''): ?>
                  <img src="<?= esc($dishImageUrl) ?>" alt="<?= esc($dish['dish_name']) ?>" class="rounded" style="width: 72px; height: 72px; object-fit: cover;" loading="lazy" decoding="async">
                <?php else: ?>
                  <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width: 72px; height: 72px;">N/A</div>
                <?php endif; ?>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                  <div>
                    <h5 class="card-title mb-1"><?= esc($dish['dish_name']) ?></h5>
                    <p class="small text-muted mb-0"><?= esc($category['category_name'] ?? 'Not linked') ?></p>
                  </div>
                  <?php if ((int) ($dish['status'] ?? 0) === 1): ?>
                    <span class="badge text-bg-success">Active</span>
                  <?php else: ?>
                    <span class="badge text-bg-danger">Inactive</span>
                  <?php endif; ?>
                </div>

                <p class="small mb-2"><?= esc(($dish['dish_desc'] ?? '') ?: 'No description') ?></p>

                <div class="small">
                  <p class="mb-1"><strong>Price:</strong> Rs. <?= esc(number_format((float) ($dish['dish_price'] ?? 0), 2)) ?></p>
                  <p class="mb-1"><strong>MRP:</strong> Rs. <?= esc(number_format((float) ($dish['dish_mrp'] ?? 0), 2)) ?></p>
                  <p class="mb-0">
                    <strong>Days:</strong>
                    <?php if ((int) ($dish['is_daily'] ?? 0) === 1): ?>
                      Daily
                    <?php elseif (!empty($selectedDayNames)): ?>
                      <?= esc(implode(', ', $selectedDayNames)) ?>
                    <?php else: ?>
                      Not linked
                    <?php endif; ?>
                  </p>
                </div>

                <div class="d-flex gap-2 mt-3">
                  <button
                    type="button"
                    class="btn btn-sm btn-primary edit-dish-btn"
                    data-id="<?= esc($dishId) ?>"
                    data-dish_name="<?= esc($dish['dish_name']) ?>"
                    data-dish_price="<?= esc($dish['dish_price']) ?>"
                    data-dish_mrp="<?= esc($dish['dish_mrp']) ?>"
                    data-dish_desc="<?= esc($dish['dish_desc'] ?? '') ?>"
                    data-day_ids="<?= esc(implode(',', $selectedDayIds)) ?>"
                    data-category_id="<?= esc($dish['category_id']) ?>"
                    data-is_daily="<?= esc($dish['is_daily'] ?? 0) ?>"
                    data-status="<?= esc($dish['status'] ?? 1) ?>"
                    data-dish_image="<?= esc($dishImageUrl) ?>"
                    data-dish_thumbnails="<?= esc($dishThumbnailUrl) ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#dishModal">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form action="/admin/dishes/delete/<?= esc($dishId) ?>" method="post" class="flex-fill" onsubmit="return confirm('Delete this dish?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info mb-0" role="alert">
        No dishes found. Add a dish to get started.
      </div>
    <?php endif; ?>
  </div>

  <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="d-flex justify-content-end mt-3">
      <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
  <?php endif; ?>
</div>



<div class="modal fade" id="dishModal" tabindex="-1" aria-labelledby="dishModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <form id="dishForm" method="post" action="/admin/dishes/create" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- HEADER -->
        <div class="modal-header">
          <h5 class="modal-title" id="dishModalLabel">Add Dish</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- BODY -->
        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label for="dish_name" class="form-label">Dish Name</label>
              <input type="text" class="form-control" id="dish_name" name="dish_name" value="<?= esc(old('dish_name')) ?>" required>
            </div>

            <div class="col-md-3">
              <label for="dish_price" class="form-label">Dish Price</label>
              <input type="number" class="form-control" id="dish_price" name="dish_price" min="0" step="0.01" value="<?= esc(old('dish_price')) ?>" required>
            </div>

            <div class="col-md-3">
              <label for="dish_mrp" class="form-label">Dish MRP</label>
              <input type="number" class="form-control" id="dish_mrp" name="dish_mrp" min="0" step="0.01" value="<?= esc(old('dish_mrp')) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="category_id" class="form-label">Category</label>
              <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $category): ?>
                  <option value="<?= esc($category['id']) ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                    <?= esc($category['category_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="1" <?= old('status', '1') === '1' ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= old('status') === '0' ? 'selected' : '' ?>>Inactive</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label d-block" for="is_daily">Daily Option</label>
              <div class="form-check form-switch pt-2">
                <input class="form-check-input" type="checkbox" id="is_daily" name="is_daily" value="1" <?= old('is_daily') ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_daily">Use as daily dish</label>
              </div>
            </div>

            <div class="col-12" id="dayChecklistWrapper">
              <label class="form-label d-block">Serve Days</label>
              <div class="row g-2">
                <?php if (!empty($days)): ?>
                  <?php foreach ($days as $day): ?>
                    <?php $checked = in_array((string) $day['id'], array_map('strval', $oldDayIds), true); ?>
                    <div class="col-6 col-md-4 col-lg-3">
                      <label class="form-check border rounded px-3 py-2 w-100 h-100">
                        <input class="form-check-input day-checkbox" type="checkbox" name="day_ids[]" value="<?= esc($day['id']) ?>" <?= $checked ? 'checked' : '' ?>>
                        <span class="form-check-label ms-2"><?= esc($day['day_name']) ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="col-12">
                    <div class="alert alert-warning mb-0">Create days first to assign them to dishes.</div>
                  </div>
                <?php endif; ?>
              </div>
              <div class="form-text">Select one or more days. This checklist is hidden when daily option is on.</div>
            </div>

            <div class="col-md-6">
              <label for="dish_image" class="form-label">Dish Image</label>
              <input type="file" class="form-control" id="dish_image" name="dish_image" accept="image/png,image/jpeg,image/jpg,image/webp">
              <div class="form-text" id="dishImageHint">Upload the main dish image. Max 2MB.</div>
              <div class="mt-2" id="dishImagePreview"></div>
            </div>

            <div class="col-md-6">
              <label for="dish_thumbnails" class="form-label">Dish Thumbnail</label>
              <input type="file" class="form-control" id="dish_thumbnails" name="dish_thumbnails" accept="image/png,image/jpeg,image/jpg,image/webp">
              <div class="form-text" id="dishThumbnailHint">Upload the thumbnail image. Max 2MB.</div>
              <div class="mt-2" id="dishThumbnailPreview"></div>
            </div>

            <div class="col-12">
              <label for="dish_desc" class="form-label">Description</label>
              <textarea class="form-control" id="dish_desc" name="dish_desc" rows="4"><?= esc(old('dish_desc')) ?></textarea>
            </div>

          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="dishSubmitBtn">Create</button>
        </div>

      </form>
      
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('dishModal');
    var form = document.getElementById('dishForm');
    var addBtn = document.getElementById('addDishBtn');

    if (!modalEl || !form || !addBtn) {
      return;
    }

    var title = document.getElementById('dishModalLabel');
    var submitBtn = document.getElementById('dishSubmitBtn');
    var dishNameInput = document.getElementById('dish_name');
    var dishPriceInput = document.getElementById('dish_price');
    var dishMrpInput = document.getElementById('dish_mrp');
    var categoryInput = document.getElementById('category_id');
    var statusInput = document.getElementById('status');
    var isDailyInput = document.getElementById('is_daily');
    var dayChecklistWrapper = document.getElementById('dayChecklistWrapper');
    var dayCheckboxes = Array.prototype.slice.call(document.querySelectorAll('.day-checkbox'));
    var dishDescInput = document.getElementById('dish_desc');
    var dishImageInput = document.getElementById('dish_image');
    var dishThumbnailInput = document.getElementById('dish_thumbnails');
    var dishImageHint = document.getElementById('dishImageHint');
    var dishThumbnailHint = document.getElementById('dishThumbnailHint');
    var dishImagePreview = document.getElementById('dishImagePreview');
    var dishThumbnailPreview = document.getElementById('dishThumbnailPreview');

    function renderImagePreview(container, imagePath, fallbackText) {
      if (imagePath) {
        container.innerHTML = '<img src="' + imagePath + '" alt="" class="img-thumbnail" style="max-height: 88px; object-fit: cover;">';
        return;
      }

      container.innerHTML = '<span class="text-muted small">' + fallbackText + '</span>';
    }

    function renderSelectedFile(input, container, fallbackText) {
      var file = input.files && input.files[0];

      if (!file) {
        renderImagePreview(container, '', fallbackText);
        return;
      }

      var objectUrl = URL.createObjectURL(file);
      container.innerHTML = '<img src="' + objectUrl + '" alt="" class="img-thumbnail" style="max-height: 88px; object-fit: cover;">';
    }

    function setSelectedDayIds(dayIds) {
      dayCheckboxes.forEach(function(checkbox) {
        checkbox.checked = dayIds.indexOf(checkbox.value) !== -1;
      });
    }

    function parseDayIds(dayIds) {
      if (!dayIds) {
        return [];
      }

      return String(dayIds)
        .split(',')
        .map(function(dayId) {
          return dayId.trim();
        })
        .filter(function(dayId) {
          return dayId !== '';
        });
    }

    function toggleDayField() {
      var isDaily = isDailyInput.checked;
      dayChecklistWrapper.classList.toggle('d-none', isDaily);

      if (isDaily) {
        setSelectedDayIds([]);
      }
    }

    function fillCreateMode(useOldInput) {
      title.textContent = 'Add Dish';
      submitBtn.textContent = 'Create';
      form.action = '/admin/dishes/create';

      if (!useOldInput) {
        form.reset();
        dishNameInput.value = '';
        dishPriceInput.value = '';
        dishMrpInput.value = '';
        categoryInput.value = '';
        statusInput.value = '1';
        isDailyInput.checked = false;
        setSelectedDayIds([]);
        dishDescInput.value = '';
      }

      dishImageInput.value = '';
      dishThumbnailInput.value = '';
      dishImageInput.required = true;
      dishThumbnailInput.required = true;
      dishImageHint.textContent = 'Upload the main dish image. Max 2MB.';
      dishThumbnailHint.textContent = 'Upload the thumbnail image. Max 2MB.';
      renderImagePreview(dishImagePreview, '', 'No image uploaded yet.');
      renderImagePreview(dishThumbnailPreview, '', 'No thumbnail uploaded yet.');
      toggleDayField();
    }

    function fillEditMode(dish) {
      title.textContent = 'Edit Dish';
      submitBtn.textContent = 'Update';
      form.action = '/admin/dishes/update/' + dish.id;
      dishNameInput.value = dish.name || '';
      dishPriceInput.value = dish.price || '';
      dishMrpInput.value = dish.mrp || '';
      categoryInput.value = dish.categoryId || '';
      statusInput.value = dish.status || '1';
      isDailyInput.checked = dish.isDaily === '1';
      setSelectedDayIds(dish.dayIds || []);
      dishDescInput.value = dish.description || '';
      dishImageInput.value = '';
      dishThumbnailInput.value = '';
      dishImageInput.required = false;
      dishThumbnailInput.required = false;
      dishImageHint.textContent = 'Leave blank to keep the existing dish image.';
      dishThumbnailHint.textContent = 'Leave blank to keep the existing thumbnail image.';
      renderImagePreview(dishImagePreview, dish.image || '', 'No dish image uploaded.');
      renderImagePreview(dishThumbnailPreview, dish.thumbnail || '', 'No thumbnail uploaded.');
      toggleDayField();
    }

    addBtn.addEventListener('click', function() {
      fillCreateMode(false);
    });

    isDailyInput.addEventListener('change', toggleDayField);

    dishImageInput.addEventListener('change', function() {
      renderSelectedFile(dishImageInput, dishImagePreview, 'No image uploaded yet.');
    });

    dishThumbnailInput.addEventListener('change', function() {
      renderSelectedFile(dishThumbnailInput, dishThumbnailPreview, 'No thumbnail uploaded yet.');
    });

    document.querySelectorAll('.edit-dish-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        fillEditMode({
          id: this.dataset.id,
          name: this.dataset.dish_name,
          price: this.dataset.dish_price,
          mrp: this.dataset.dish_mrp,
          description: this.dataset.dish_desc,
          dayIds: parseDayIds(this.dataset.day_ids),
          categoryId: this.dataset.category_id,
          isDaily: this.dataset.is_daily,
          status: this.dataset.status,
          image: this.dataset.dish_image,
          thumbnail: this.dataset.dish_thumbnails
        });
      });
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
      fillCreateMode(false);
    });

    <?php if ($modalMode === 'create'): ?>
      fillCreateMode(true);
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    <?php elseif ($modalMode === 'edit'): ?>
      fillEditMode({
        id: '<?= esc((string) $modalDishId) ?>',
        name: '<?= esc((string) old('dish_name')) ?>',
        price: '<?= esc((string) old('dish_price')) ?>',
        mrp: '<?= esc((string) old('dish_mrp')) ?>',
        description: '<?= esc((string) old('dish_desc')) ?>',
        dayIds: <?= json_encode(array_values(array_map('strval', $oldDayIds)), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        categoryId: '<?= esc((string) old('category_id')) ?>',
        isDaily: '<?= old('is_daily') ? '1' : '0' ?>',
        status: '<?= esc((string) old('status', '1')) ?>',
        image: '',
        thumbnail: ''
      });
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    <?php else: ?>
      fillCreateMode(false);
    <?php endif; ?>
  });
</script>

<?= view('/partials/adminfooter') ?>
