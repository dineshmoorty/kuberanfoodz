<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('/images/logo.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="/admin/dashboard">
          <img src="<?= esc($company_logo ?? base_url('/images/logo.png')) ?>" alt="Kuberan Foods Logo" width="30" height="30" class="d-inline-block align-text-top">
          <?= esc($company_name ?? 'Kuberan Foods Admin') ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarNav">
          <ul class="navbar-nav ms-auto d-block d-lg-none">
            <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/settings">Settings</a></li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/menu">Menu</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/dishes">Dishes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/groceries">Groceries</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admin/logout">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>