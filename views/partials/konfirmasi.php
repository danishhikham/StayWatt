<?php partial('modal_open', ['judul_modal' => $k_judul, 'tutup' => $k_batal]); ?>
<p class="text-ink2 mb-6"><?= e($k_teks) ?></p>
<form method="post" action="<?= e(u($k_aksi)) ?>" class="flex justify-end gap-2"><?php partial('csrf'); ?>
  <a href="<?= e(u($k_batal)) ?>" class="btn-sekunder">Batal</a>
  <button type="submit" class="btn-utama">Hapus</button>
</form>
<?php partial('modal_close'); ?>
