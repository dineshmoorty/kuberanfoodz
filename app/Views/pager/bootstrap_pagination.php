<?php

use CodeIgniter\Pager\PagerRenderer;

/** @var PagerRenderer $pager */
$pager->setSurroundCount(2);
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
  <ul class="pagination pagination-sm">


    <?php foreach ($pager->links() as $link) : ?>
      <li class="page-item<?= $link['active'] ? ' active' : '' ?>">
        <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
      </li>
    <?php endforeach ?>


  </ul>
</nav>