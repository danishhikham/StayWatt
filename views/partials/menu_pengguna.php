<details class="relative ml-auto">
  <summary class="cursor-pointer p-1 text-ink2 hover:text-ink transition-colors" aria-label="Menu pengguna"><?= icon('ellipsis-vertical', 18) ?></summary>
  <div class="absolute <?= e($panel) ?> w-48 bg-surface2 rounded-card p-2 z-30 flex flex-col gap-1">
    <?php partial('toggle_tema'); ?>
    <form method="post" action="<?= e(u('/keluar')) ?>"><?php partial('csrf'); ?><button type="submit" class="menu-item"><?= icon('log-out', 16) ?>Keluar</button></form>
  </div>
</details>
