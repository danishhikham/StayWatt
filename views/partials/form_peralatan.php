<?php partial('modal_open', ['judul_modal' => $form['id'] ? 'Ubah peralatan' : 'Tambah peralatan', 'tutup' => '/peralatan']); ?>
<form method="post" action="<?= e(u($form['id'] ? '/peralatan/' . $form['id'] : '/peralatan')) ?>" id="form-peralatan" class="flex flex-col gap-4" novalidate>
  <?php partial('csrf'); ?>
  <?php if (!$form['id'] && count($referensi)): ?>
  <div>
    <label class="label" for="referensi">Pilih dari daftar (opsional)</label>
    <select id="referensi" class="input">
      <option value="">Isi manual</option>
      <?php foreach ($referensi as $r): ?><option value="<?= e($r['id']) ?>" data-nama="<?= e($r['nama']) ?>" data-daya="<?= e($r['daya_default_w']) ?>" data-kategori="<?= e($r['kategori_id']) ?>"><?= e($r['nama']) ?> · <?= e($r['daya_default_w']) ?> W</option><?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>
  <?php partial('field', ['nama' => 'nama', 'label' => 'Nama peralatan', 'tipe' => 'text', 'nilai' => $form['v']['nama'], 'galat' => $form['errors']['nama'] ?? null, 'attrs' => 'maxlength="100" autocomplete="off"']); ?>
  <div>
    <label class="label" for="kategori_id">Kategori</label>
    <select id="kategori_id" name="kategori_id" class="input">
      <option value="">Pilih kategori</option>
      <?php foreach ($kategori as $k): ?><option value="<?= e($k['id']) ?>"<?= (int)$form['v']['kategori_id'] === (int)$k['id'] ? ' selected' : '' ?>><?= e($k['nama']) ?></option><?php endforeach; ?>
    </select>
    <?php if (!empty($form['errors']['kategori_id'])): ?><p class="pesan-err"><?= e($form['errors']['kategori_id']) ?></p><?php endif; ?>
  </div>
  <?php partial('field', ['nama' => 'daya_w', 'label' => 'Daya (W)', 'tipe' => 'number', 'nilai' => $form['v']['daya_w'], 'galat' => $form['errors']['daya_w'] ?? null, 'attrs' => 'min="1" max="10000" inputmode="numeric"']); ?>
  <div class="grid grid-cols-2 gap-4">
    <div><span class="label">Jumlah unit</span><?php partial('stepper', ['s_nama' => 'jumlah', 's_nilai' => $form['v']['jumlah'], 's_min' => 1, 's_max' => 99, 's_satuan' => 'unit']); ?><?php if (!empty($form['errors']['jumlah'])): ?><p class="pesan-err"><?= e($form['errors']['jumlah']) ?></p><?php endif; ?></div>
    <div><span class="label">Durasi per hari</span><?php partial('stepper', ['s_nama' => 'jam', 's_nilai' => $form['v']['jam'], 's_min' => 0, 's_max' => 24, 's_satuan' => 'jam']); ?><?php if (!empty($form['errors']['jam'])): ?><p class="pesan-err"><?= e($form['errors']['jam']) ?></p><?php endif; ?></div>
  </div>
  <div class="bg-surface2 rounded-card p-4 flex flex-col gap-3" aria-live="polite">
    <span class="text-[12px] text-ink2">Estimasi per bulan</span>
    <?php partial('baris_metrik', ['dot' => 'kwh', 'label' => 'Konsumsi', 'nilai' => $est['kwh'], 'idn' => 'est-kwh']); ?>
    <?php partial('baris_metrik', ['dot' => 'biaya', 'label' => 'Biaya', 'nilai' => $est['biaya'], 'idn' => 'est-biaya']); ?>
    <?php partial('baris_metrik', ['dot' => 'co2', 'label' => 'Emisi', 'nilai' => $est['emisi'], 'idn' => 'est-emisi']); ?>
  </div>
  <div class="flex justify-end gap-2 mt-1">
    <a href="<?= e(u('/peralatan')) ?>" class="btn-sekunder">Batal</a>
    <button type="submit" class="btn-utama">Simpan</button>
  </div>
</form>
<?php partial('modal_close'); ?>
