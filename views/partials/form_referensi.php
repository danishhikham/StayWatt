<?php partial('modal_open', ['judul_modal' => $formRef['id'] ? 'Ubah referensi' : 'Tambah referensi', 'tutup' => '/admin']); ?>
<form method="post" action="<?= e(u($formRef['id'] ? '/admin/referensi/' . $formRef['id'] : '/admin/referensi')) ?>" class="flex flex-col gap-4" novalidate>
  <?php partial('csrf'); ?>
  <?php partial('field', ['nama' => 'nama', 'label' => 'Nama peralatan', 'tipe' => 'text', 'nilai' => $formRef['v']['nama'], 'galat' => $formRef['errors']['nama'] ?? null, 'attrs' => 'maxlength="100"']); ?>
  <div>
    <label class="label" for="kategori_id">Kategori</label>
    <select id="kategori_id" name="kategori_id" class="input">
      <option value="">Pilih kategori</option>
      <?php foreach ($kategori as $k): ?><option value="<?= e($k['id']) ?>"<?= (int)$formRef['v']['kategori_id'] === (int)$k['id'] ? ' selected' : '' ?>><?= e($k['nama']) ?></option><?php endforeach; ?>
    </select>
    <?php if (!empty($formRef['errors']['kategori_id'])): ?><p class="pesan-err"><?= e($formRef['errors']['kategori_id']) ?></p><?php endif; ?>
  </div>
  <?php partial('field', ['nama' => 'daya', 'label' => 'Daya bawaan (W)', 'tipe' => 'number', 'nilai' => $formRef['v']['daya'], 'galat' => $formRef['errors']['daya'] ?? null, 'attrs' => 'min="1" max="10000"']); ?>
  <div class="flex justify-end gap-2 mt-1"><a href="<?= e(u('/admin')) ?>" class="btn-sekunder">Batal</a><button type="submit" class="btn-utama">Simpan</button></div>
</form>
<?php partial('modal_close'); ?>
