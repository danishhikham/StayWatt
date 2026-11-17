<?php
  $dari = $p_total ? ($p_page - 1) * $p_per + 1 : 0;
  $sampai = min($p_page * $p_per, $p_total);
  $maks = max(1, (int)ceil($p_total / $p_per));
  $qs = function ($pg) use ($p_ekstra, $p_per) { return http_build_query(array_merge($p_ekstra, ['per' => $p_per, 'page' => $pg])); };
?>
<div class="flex items-center justify-end gap-4 mt-4 text-[12px] text-ink2">
  <span class="tabular-nums"><?= e($dari) ?>–<?= e($sampai) ?> dari <?= e($p_total) ?></span>
  <form method="get" class="flex items-center">
    <?php foreach ($p_ekstra as $k => $v): ?><input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>"><?php endforeach; ?>
    <select name="per" onchange="this.form.submit()" class="bg-transparent text-ink2 cursor-pointer outline-none">
      <?php foreach ([10, 25, 50] as $n): ?><option value="<?= $n ?>"<?= $n === $p_per ? ' selected' : '' ?>><?= $n ?> per halaman</option><?php endforeach; ?>
    </select>
  </form>
  <?php if ($p_page > 1): ?><a href="?<?= e($qs($p_page - 1)) ?>" class="btn-sekunder w-10 px-0" aria-label="Sebelumnya"><?= icon('chevron-left', 16) ?></a><?php else: ?><span class="btn-sekunder w-10 px-0 opacity-40" aria-hidden="true"><?= icon('chevron-left', 16) ?></span><?php endif; ?>
  <?php if ($p_page < $maks): ?><a href="?<?= e($qs($p_page + 1)) ?>" class="btn-sekunder w-10 px-0" aria-label="Berikutnya"><?= icon('chevron-right', 16) ?></a><?php else: ?><span class="btn-sekunder w-10 px-0 opacity-40" aria-hidden="true"><?= icon('chevron-right', 16) ?></span><?php endif; ?>
</div>
