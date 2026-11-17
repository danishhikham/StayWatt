<div class="fixed inset-0 z-40 flex items-end sm:items-center justify-center bg-scrim" role="dialog" aria-modal="true" aria-labelledby="modal-judul">
  <a href="<?= e(u($tutup)) ?>" class="absolute inset-0" aria-label="Tutup"></a>
  <div class="relative w-full sm:max-w-md bg-surface rounded-t-card sm:rounded-card p-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-5">
      <h2 id="modal-judul" class="judul-kartu"><?= e($judul_modal) ?></h2>
      <a href="<?= e(u($tutup)) ?>" class="text-ink2 hover:text-ink transition-colors" aria-label="Tutup"><?= icon('x', 18) ?></a>
    </div>
