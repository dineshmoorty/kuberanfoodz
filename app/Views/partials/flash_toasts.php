<?php
// A small reusable partial to show flash messages (success/error/info/warning/validation)
// using Bootstrap 5 toasts.
// Include this in your layout (e.g. adminheader) to show messages on every page.

$session = session();
$toastItems = [];

// Map flash keys to toast metadata.
$flashMap = [
  'success' => ['title' => 'Success', 'variant' => 'success'],
  'error'   => ['title' => 'Error', 'variant' => 'danger'],
  'warning' => ['title' => 'Warning', 'variant' => 'warning'],
  'info'    => ['title' => 'Info', 'variant' => 'info'],
  'loading' => ['title' => 'Loading', 'variant' => 'primary'],
];

foreach ($flashMap as $key => $meta) {
  $message = $session->getFlashdata($key);
  if (!$message) {
    continue;
  }

  $toastItems[] = [
    'title' => $meta['title'],
    'variant' => $meta['variant'],
    'message' => $message,
    'isList' => false,
    'isLoading' => $key === 'loading',
  ];
}

// Validation errors may be stored as the validator instance or an array
$validation = $session->getFlashdata('validation');
if ($validation) {
  $errors = [];

  if (is_object($validation) && method_exists($validation, 'getErrors')) {
    $errors = $validation->getErrors();
  } elseif (is_array($validation)) {
    $errors = $validation;
  }

  if (!empty($errors)) {
    $toastItems[] = [
      'title' => 'Validation Failed',
      'variant' => 'danger',
      'message' => $errors,
      'isList' => true,
    ];
  }
}

if (empty($toastItems)) {
  return;
}
?>

<style>
  /* Toast loading progress bar */
  .toast-loading .toast-body {
    position: relative;
  }

  .toast-loading .toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: rgba(0, 0, 0, 0.08);
    border-radius: 999px;
    overflow: hidden;
  }

  .toast-loading .toast-progress>div {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    animation: toast-progress 10s linear forwards;
  }

  @keyframes toast-progress {
    to {
      width: 0%;
    }
  }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
  <?php foreach ($toastItems as $idx => $toast): ?>
    <div class="toast shadow<?= !empty($toast['isLoading']) ? ' toast-loading' : '' ?>" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
      <div class="toast-header bg-<?= esc($toast['variant']) ?> text-white">
        <strong class="me-auto"><?= esc($toast['title']) ?></strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <?php if (!empty($toast['isLoading'])): ?>
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>
            <span class="fw-medium">Processing…</span>
          </div>
        <?php endif; ?>
        <?php if ($toast['isList'] && is_array($toast['message'])): ?>
          <ul class="mb-0">
            <?php foreach ($toast['message'] as $error): ?>
              <li><?= esc($error) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <?= esc($toast['message']) ?>
        <?php endif; ?>
        <?php if (!empty($toast['isLoading'])): ?>
          <div class="toast-progress">
            <div></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast').forEach(function(element) {
      var toast = new bootstrap.Toast(element);
      toast.show();
    });
  });
</script>