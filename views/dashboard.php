<?php partial('app_open', ['halaman' => 'dashboard']); ?>
<?php if ($kosong): ?>
  <h1 class="judul-halaman mb-6">Halo, <?= e($namaDepan) ?></h1>
  <?php partial('empty', ['e_ikon' => 'settings', 'e_judul' => 'Belum ada peralatan', 'e_teks' => 'Tambahkan peralatan listrikmu untuk melihat estimasi konsumsi, biaya, dan emisi.', 'e_href' => '/peralatan/baru', 'e_label' => 'Tambah peralatan']); ?>
<?php else: ?>
<div class="flex items-start justify-between gap-4">
  <div>
    <h1 class="judul-halaman">Halo, <?= e($namaDepan) ?></h1>
    <?php if ($kalimat): ?><p class="mt-4 text-ink2"><?= e($kalimat) ?><?php if ($aktif): ?> <a href="<?= e(u('/rekomendasi')) ?>" class="text-accent font-semibold">Lihat saran →</a><?php endif; ?></p><?php endif; ?>
  </div>
  <form method="get" class="relative shrink-0 text-ink2">
    <select name="periode" onchange="this.form.submit()" aria-label="Periode" class="appearance-none bg-transparent pr-6 cursor-pointer outline-none">
      <?php foreach ($opsi as $o): ?><option value="<?= e($o['v']) ?>"<?= $o['v'] === $cur['periode'] ? ' selected' : '' ?>><?= e($o['l']) ?></option><?php endforeach; ?>
    </select>
    <span class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2"><?= icon('chevron-down', 14) ?></span>
  </form>
</div>

<div class="mt-8 grid md:grid-cols-[5fr_3fr] bg-surface rounded-card">
  <div class="p-6">
    <span class="flex items-center gap-2 text-[12px] text-ink2"><span class="w-2 h-2 rounded-full bg-kwh"></span>Total konsumsi</span>
    <div class="mt-6 flex items-baseline gap-3"><span class="text-[56px] leading-none font-medium tabular-nums"><?= e(angka($cur['total']['kwh'], 1)) ?></span><span class="text-[18px] text-ink2">kWh</span></div>
    <div class="mt-4"><?php partial('tren', ['pct' => $pct, 'sufiks' => $prevNama ? 'dari ' . $prevNama : '', 'tanda' => false]); ?></div>
  </div>
  <div class="md:border-l border-t md:border-t-0 border-line flex flex-col">
    <div class="p-6 flex-1"><?php partial('baris_metrik', ['dot' => 'biaya', 'label' => 'Estimasi biaya', 'nilai' => rupiah($cur['total']['biaya']), 'besar' => true, 'pct' => $pct]); ?></div>
    <div class="p-6 flex-1 border-t border-line"><?php partial('baris_metrik', ['dot' => 'co2', 'label' => 'Emisi', 'nilai' => angka($cur['total']['emisi'], 2), 'satuan' => 'kg CO₂e', 'besar' => true, 'pct' => $pct]); ?></div>
  </div>
</div>

<div class="mt-4 grid lg:grid-cols-[3fr_2fr] gap-4">
  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
    <h2 class="judul-kartu">Konsumsi bulanan</h2>
    <p class="text-[12px] text-ink3 mt-1">Penggunaan dalam kWh</p>
    <div class="relative h-[240px] mt-4"><canvas id="grafik" role="img" aria-label="Grafik konsumsi bulanan dalam kWh"></canvas></div>
    <script type="application/json" id="grafik-data"><?= json_encode($grafik, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
  <?php partial('kartu_close'); ?>
  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
    <div class="flex items-start justify-between gap-3 mb-6">
      <div><h2 class="judul-kartu">Peralatan terboros</h2><p class="text-[12px] text-ink3 mt-1">Porsi konsumsi <?= $aktif ? 'bulan ini' : 'periode ini' ?></p></div>
      <a href="<?= e(u('/peralatan')) ?>" class="text-accent font-semibold text-[12px] shrink-0 mt-1">Lihat semua →</a>
    </div>
    <?php partial('bar_list', ['items' => $terboros]); ?>
  <?php partial('kartu_close'); ?>
</div>
<?php endif; ?>
<?php partial('app_close'); ?>
