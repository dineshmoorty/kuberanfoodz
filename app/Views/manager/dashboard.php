<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">
  <?= view('/partials/flash_toasts') ?>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-1">Manager Dashboard</h1>
        <p class="text-muted mb-0">Dummy dashboard for role-based login testing.</p>
      </div>
      <a href="/admin/logout" class="btn btn-outline-danger">Logout</a>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">Profile</h5>
            <p class="mb-1"><strong>Name:</strong> <?= esc($profile['name'] ?? session()->get('admin_name')) ?></p>
            <p class="mb-1"><strong>Username:</strong> <?= esc($profile['username'] ?? session()->get('admin')) ?></p>
            <p class="mb-0"><strong>Role:</strong> Manager</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">Company</h5>
            <p class="mb-1"><strong>Company:</strong> <?= esc($company['company_name'] ?? 'Not linked') ?></p>
            <p class="mb-1"><strong>Company ID:</strong> <?= esc((string) ($company['id'] ?? ($profile['company_id'] ?? ''))) ?></p>
            <p class="mb-0"><strong>Status:</strong> Active demo dashboard</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
