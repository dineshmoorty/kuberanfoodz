<main class="container-fluid" style="margin-top: 56px;">
  <div class="row">

    <?php
    $currentPath = uri_string();
    $isActive = function (string $path) use ($currentPath) {
      $path = trim($path, '/');
      return strpos($currentPath, $path) === 0 ? 'active' : '';
    };
    ?>

    <!-- Sidebar -->
    <div class="col-lg-2 d-none d-lg-block bg-light sidebar position-fixed" style="top: 56px; height: calc(100vh - 56px);">

      <ul class="nav flex-column pt-2 pb-4">
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

    <!-- Content -->
    <div class="col-lg-10 offset-lg-2 p-3">
