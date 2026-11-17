<ul class="flex flex-col gap-5">
  <?php foreach ($items as $it): ?>
  <li>
    <div class="flex items-baseline justify-between gap-3"><span class="font-semibold"><?= e($it['label']) ?></span><span class="text-[12px] text-ink2 tabular-nums"><?= e($it['teks']) ?></span></div>
    <div class="mt-2 h-[2px] rounded-full bg-accent-dim"><div class="h-full rounded-full bg-accent" style="width:<?= e(jsNum($it['pct'])) ?>%"></div></div>
  </li>
  <?php endforeach; ?>
</ul>
