<?php partial('kartu_open', ['pad' => 'p-10', 'kelas' => 'text-center']); ?>
  <span class="inline-grid place-items-center w-10 h-10 rounded-full bg-surface2 text-ink2 mb-4"><?= icon($e_ikon, 18) ?></span>
  <h2 class="judul-kartu"><?= e($e_judul) ?></h2>
  <p class="text-ink2 mt-1"><?= e($e_teks) ?></p>
  <?php if (!empty($e_href)): ?><a href="<?= e(u($e_href)) ?>" class="btn-utama mt-5"><?= e($e_label) ?></a><?php endif; ?>
<?php partial('kartu_close'); ?>
