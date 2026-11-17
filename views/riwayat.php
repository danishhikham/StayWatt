<?php partial('app_open', ['halaman' => 'riwayat']); ?>
<div class="flex items-start justify-between gap-4 mb-6">
  <div>
    <p class="eyebrow">Rekap estimasi</p>
    <h1 class="judul-halaman">Riwayat Penggunaan</h1>
    <p class="text-ink2 mt-1">Bandingkan konsumsi dan biaya dari waktu ke waktu.</p>
  </div>
  <form method="get" class="relative shrink-0">
    <input type="hidden" name="per" value="<?= e($per) ?>">
    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-ink2"><?= icon('history', 16) ?></span>
    <select name="rentang" onchange="this.form.submit()" aria-label="Rentang" class="appearance-none h-10 rounded-ctl bg-surface border border-line pl-9 pr-9 font-semibold cursor-pointer outline-none">
      <?php foreach ([3, 6, 12] as $n): ?><option value="<?= $n ?>"<?= $n === $rentang ? ' selected' : '' ?>><?= $n ?> bulan terakhir</option><?php endforeach; ?>
    </select>
    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink2"><?= icon('chevron-down', 16) ?></span>
  </form>
</div>
<?php if (!$total): ?>
  <?php partial('empty', ['e_ikon' => 'history', 'e_judul' => 'Belum ada riwayat', 'e_teks' => 'Riwayat muncul setelah kamu menambahkan peralatan.', 'e_href' => '/peralatan/baru', 'e_label' => 'Tambah peralatan']); ?>
<?php else: ?>
  <?php partial('kartu_open', ['pad' => 'p-0', 'kelas' => 'overflow-x-auto']); ?>
    <div class="min-w-[680px]">
      <div class="grid grid-cols-[1.3fr_1fr_1fr_1fr_1fr_40px] items-center px-6 py-3 text-[12px] text-ink2 border-b border-line">
        <span>Periode</span><span class="text-right">Konsumsi</span><span class="text-right">Biaya</span><span class="text-right">CO₂e</span><span class="text-right">Perubahan</span><span></span>
      </div>
      <?php foreach ($baris as $i => $b): ?>
      <details class="group border-b border-line last:border-b-0"<?= $i === 0 && $page === 1 ? ' open' : '' ?>>
        <summary class="grid grid-cols-[1.3fr_1fr_1fr_1fr_1fr_40px] items-center px-6 py-5 cursor-pointer tabular-nums">
          <span class="font-semibold"><?= e(preg_replace('/^(\S{3})\S*/u', '$1', labelPeriode($b['periode']))) ?></span>
          <span class="text-right text-ink2"><?= e(angka($b['total']['kwh'], 1)) ?> kWh</span>
          <span class="text-right text-ink2"><?= e(rupiah($b['total']['biaya'])) ?></span>
          <span class="text-right text-ink2"><?= e(angka($b['total']['emisi'], 2)) ?> kg</span>
          <span class="text-right"><?php if ($b['pct'] === null): ?><span class="text-ink2">—</span><?php else: ?><?php partial('tren', ['pct' => $b['pct'], 'sufiks' => '', 'tanda' => true]); ?><?php endif; ?></span>
          <span class="flex justify-end text-ink2"><span class="group-open:hidden"><?= icon('chevron-down', 16) ?></span><span class="hidden group-open:inline"><?= icon('chevron-up', 16) ?></span></span>
        </summary>
        <div class="px-6 pb-6">
          <p class="text-[11px] tracking-wider uppercase text-ink3 mb-3">Rincian peralatan</p>
          <div class="grid grid-cols-3 gap-x-8 gap-y-3">
            <?php foreach ($b['items'] as $it): ?><div class="flex justify-between gap-3"><span class="text-ink2"><?= e($it['nama']) ?></span><span class="font-semibold tabular-nums"><?= e(angka($it['kwh'], 1)) ?> kWh</span></div><?php endforeach; ?>
          </div>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  <?php partial('kartu_close'); ?>
  <?php partial('pager', ['p_total' => $total, 'p_page' => $page, 'p_per' => $per, 'p_ekstra' => ['rentang' => $rentang]]); ?>
<?php endif; ?>
<?php partial('app_close'); ?>
