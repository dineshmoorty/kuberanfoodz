<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('/images/logo.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <style>
    :root {
      --admin-bg: #7d8f43;
      --admin-text: #ffffff;
      --admin-muted: rgba(255, 255, 255, 0.9);
      --admin-hover: rgba(255, 255, 255, 0.20);
    }

    .navbar.bg-light,
    footer.bg-light {
      background-color: var(--admin-bg) !important;
    }

    .navbar .navbar-brand,
    .navbar .nav-link {
      color: var(--admin-text) !important;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link:focus {
      color: var(--admin-text);
      opacity: 0.65;
    }

    .navbar .nav-link.active {
      color: var(--admin-text) !important;
      background-color: rgba(255, 255, 255, 0.15);
      border-radius: 0.50rem;
      font-weight: 600;
    }

    .sidebar {
      background-color: var(--admin-bg) !important;
    }

    .sidebar .nav-link {
      color: var(--admin-muted) !important;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      color: var(--admin-text) !important;
      background-color: var(--admin-hover) !important;
    }

    .sidebar .nav-link.active {
      font-weight: 600;
    }

    .pagination .page-item .page-link {
      color: #ffffff;
      background-color: #28a745;
      border-color: #28a745;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item .page-link:hover {
      color: #ffffff;
      background-color: #218838;
      border-color: #1e7e34;
    }

    .pagination .page-item.disabled .page-link {
      color: #e9ecef;
      background-color: #ced4da;
      border-color: #dee2e6;
    }

    .pagination {
      margin: 0;
      padding: 0;
    }
  </style>
</head>

<body >
  <?php
  $currentPath = uri_string();
  $isActive = function (string $path) use ($currentPath) {
    $path = trim($path, '/');
    return strpos($currentPath, $path) === 0 ? 'active' : '';
  };
  ?>
  <?= view('/partials/site_loader') ?>
  <?= view('/partials/flash_toasts') ?>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top">
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
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/dashboard') ?>" href="/admin/dashboard"><i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Dashboard</a></li>
            <!-- <li class="nav-item"><a class="nav-link <?= $isActive('admin/menu') ?>" href="/admin/menu"><i class="bi bi-list-task me-1" aria-hidden="true"></i>Menu</a></li> -->
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/days') ?>" href="/admin/days"><i class="bi bi-calendar-day me-1" aria-hidden="true"></i>Days</a></li>
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/categories') ?>" href="/admin/categories"><i class="bi bi-tags me-1" aria-hidden="true"></i>Categories</a></li>
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/dishes') ?>" href="/admin/dishes"><i class="bi bi-egg-fried me-1" aria-hidden="true"></i>Dishes</a></li>
            <!-- <li class="nav-item"><a class="nav-link <?= $isActive('admin/groceries') ?>" href="/admin/groceries"><i class="bi bi-cart3 me-1" aria-hidden="true"></i>Groceries</a></li> -->
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/settings') ?>" href="/admin/settings"><i class="bi bi-gear me-1" aria-hidden="true"></i>Settings</a></li>
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/roles') ?>" href="/admin/roles"><i class="bi bi-diagram-3 me-1" aria-hidden="true"></i>Roles</a></li>
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/profiles') ?: $isActive('admin/profile') ?>" href="/admin/profiles"><i class="bi bi-person-circle me-1" aria-hidden="true"></i>Profiles</a></li>
            <li class="nav-item"><a class="nav-link <?= $isActive('admin/logout') ?>" href="/admin/logout"><i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var navCollapse = document.getElementById('navbarNav');
        var toggler = document.querySelector('.navbar-toggler');
        if (!navCollapse) return;

        var collapseInstance = null;
        if (window.bootstrap && bootstrap.Collapse) {
          collapseInstance = bootstrap.Collapse.getInstance(navCollapse) || new bootstrap.Collapse(navCollapse, {
            toggle: false
          });
        }

        var hideMenu = function() {
          if (window.innerWidth < 992) {
            if (collapseInstance) {
              collapseInstance.hide();
            } else {
              navCollapse.classList.remove('show');
            }
          }
        };

        // Close menu when a nav link is clicked
        navCollapse.querySelectorAll('a.nav-link').forEach(function(link) {
          link.addEventListener('click', hideMenu);
        });

        // Close when clicking outside the menu
        document.addEventListener('click', function(event) {
          if (window.innerWidth >= 992) return;
          if (!navCollapse.classList.contains('show')) return;
          if (navCollapse.contains(event.target) || (toggler && toggler.contains(event.target))) return;

          hideMenu();
        });
      });
    </script>
  </header>