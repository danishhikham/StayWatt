<div>
  <label class="label" for="<?= e($nama) ?>"><?= e($label) ?></label>
  <input class="input" id="<?= e($nama) ?>" name="<?= e($nama) ?>" type="<?= e(!empty($tipe) ? $tipe : 'text') ?>" value="<?= e($nilai ?? '') ?>"<?= !empty($attrs) ? ' ' . $attrs : '' ?>>
  <?php if (!empty($galat)): ?><p class="pesan-err"><?= e($galat) ?></p><?php endif; ?>
</div>
