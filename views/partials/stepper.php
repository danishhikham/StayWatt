<div class="flex items-center justify-between h-10 w-[142px] rounded-ctl bg-surface2 border border-line" data-stepper-wrap>
  <button type="button" data-stepper="-1" class="w-10 h-full grid place-items-center text-ink2 hover:text-ink transition-colors" aria-label="Kurangi"><?= icon('minus', 16) ?></button>
  <span class="flex items-baseline gap-0.5"><input type="number" id="<?= e($s_nama) ?>" name="<?= e($s_nama) ?>" value="<?= e(jsNum($s_nilai)) ?>" min="<?= e($s_min) ?>" max="<?= e($s_max) ?>" step="any" inputmode="decimal"<?= !empty($s_data) ? ' ' . $s_data : '' ?> class="w-9 bg-transparent text-right font-semibold tabular-nums outline-none"><span class="text-[12px] text-ink2"><?= e($s_satuan) ?></span></span>
  <button type="button" data-stepper="1" class="w-10 h-full grid place-items-center text-ink2 hover:text-ink transition-colors" aria-label="Tambah"><?= icon('plus', 16) ?></button>
</div>
