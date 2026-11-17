<?php $warna = ['kwh' => 'bg-kwh', 'biaya' => 'bg-biaya', 'co2' => 'bg-co2']; ?>
<div class="flex items-center justify-between gap-4">
  <span class="flex items-center gap-2 text-[12px] text-ink2"><span class="w-2 h-2 rounded-full <?= e($warna[$dot] ?? '') ?>"></span><?= e($label) ?></span>
  <span class="text-right">
    <span class="<?= !empty($besar) ? 'text-[20px]' : 'text-[14px]' ?> font-medium tabular-nums"<?= !empty($idn) ? ' id="' . $idn . '"' : '' ?>><?= e($nilai) ?></span><?php if (!empty($satuan)): ?> <span class="text-[12px] text-ink2"><?= e($satuan) ?></span><?php endif; ?>
    <?php if (isset($pct)): ?><span class="block mt-1"><?php partial('tren', ['pct' => $pct, 'sufiks' => '', 'tanda' => false]); ?></span><?php endif; ?>
  </span>
</div>
