<?= view('/partials/adminheader') ?>
<?= view('/partials/adminsidebar') ?>

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Categories</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal" id="addCategoryBtn">
      <i class="bi bi-plus-lg"></i> Add Category
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th style="width: 10%">ID</th>
          <th>Category Name</th>
          <th class="text-center" style="width: 20%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($categories)): ?>
          <?php foreach ($categories as $category): ?>
            <tr>
              <td><?= esc($category['id']) ?></td>
              <td><?= esc($category['category_name']) ?></td>
              <td class="d-flex justify-content-center gap-2">
                <button
                  type="button"
                  class="btn btn-sm btn-primary me-1 edit-category-btn"
                  data-id="<?= esc($category['id']) ?>"
                  data-category_name="<?= esc($category['category_name']) ?>"
                  data-bs-toggle="modal"
                  data-bs-target="#categoryModal">
                  <i class="bi bi-pencil"></i>
                </button>
                <form action="/admin/categories/delete/<?= esc($category['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this category?');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No categories found. Add a new category to get started.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
    <div class="d-flex justify-content-center m-1">
      <?= $pager->links('default', 'bootstrap_pagination') ?>
    </div>
  <?php endif; ?>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="categoryForm" method="post" action="/admin/categories/create">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="category_id" value="">
          <div class="mb-3">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="category_name" name="category_name" required maxlength="100" placeholder="e.g. Beverages">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="categorySubmitBtn">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('addCategoryBtn').addEventListener('click', function() {
    document.getElementById('categoryForm').action = '/admin/categories/create';
    document.getElementById('categoryModalLabel').textContent = 'Add Category';
    document.getElementById('categorySubmitBtn').textContent = 'Create';
    document.getElementById('category_id').value = '';
    document.getElementById('category_name').value = '';
  });

  document.querySelectorAll('.edit-category-btn').forEach(function(button) {
    button.addEventListener('click', function() {
      var id = this.dataset.id;
      var name = this.dataset.category_name;

      document.getElementById('categoryForm').action = '/admin/categories/update/' + id;
      document.getElementById('categoryModalLabel').textContent = 'Edit Category';
      document.getElementById('categorySubmitBtn').textContent = 'Update';
      document.getElementById('category_id').value = id;
      document.getElementById('category_name').value = name;
    });
  });

  var categoryModalEl = document.getElementById('categoryModal');
  categoryModalEl.addEventListener('hidden.bs.modal', function() {
    document.getElementById('categoryForm').action = '/admin/categories/create';
    document.getElementById('categoryModalLabel').textContent = 'Add Category';
    document.getElementById('categorySubmitBtn').textContent = 'Create';
    document.getElementById('category_id').value = '';
    document.getElementById('category_name').value = '';
  });
</script>

<?= view('/partials/adminfooter') ?>