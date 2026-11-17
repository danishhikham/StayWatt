<?php partial('app_open', ['halaman' => 'simulasi']); ?>
<div class="flex items-start justify-between gap-4 mb-6">
  <div>
    <p class="eyebrow">Eksperimen tanpa risiko</p>
    <h1 class="judul-halaman">Simulasi Perubahan Kebiasaan</h1>
    <p class="text-ink2 mt-1">Atur durasi baru dan lihat dampaknya secara langsung.</p>
  </div>
  <button type="button" data-reset class="btn-sekunder shrink-0"><?= icon('rotate-ccw', 16) ?>Reset</button>
</div>
<?php if (!$bulan): ?>
  <?php partial('empty', ['e_ikon' => 'monitor-dot', 'e_judul' => 'Belum ada data untuk disimulasikan', 'e_teks' => 'Tambahkan peralatan lebih dulu.', 'e_href' => '/peralatan/baru', 'e_label' => 'Tambah peralatan']); ?>
<?php else: ?>
  <?php partial('kartu_open', ['pad' => 'px-6 py-4', 'kelas' => 'flex items-center gap-3']); ?>
    <span class="text-ink2"><?= icon('shield-check', 18) ?></span>
    <p class="text-ink2"><strong class="font-semibold text-ink">Simulasi tidak mengubah data asli.</strong> Hasil hanya tersimpan selama halaman ini dibuka.</p>
  <?php partial('kartu_close'); ?>
  <div class="mt-4 grid lg:grid-cols-[660fr_290fr] gap-4 items-start" id="simulasi">
    <?php partial('kartu_open', ['pad' => 'p-0', 'kelas' => '']); ?>
      <div class="flex items-center justify-between px-6 py-5"><h2 class="judul-kartu">Durasi pemakaian</h2><span class="text-[12px] text-ink3">Jam per hari</span></div>
      <?php
        $urut = $bulan['items'];
        usort($urut, function ($a, $b) { return $a['id'] <=> $b['id']; });
        foreach ($urut as $it):
          $baru = $perubahan[$it['id']] ?? $it['jamPerHari'];
          $b = $hasil['baris'][$it['id']];
      ?>
      <div class="flex items-center gap-4 px-6 py-4 border-t border-line" data-baris="<?= e($it['id']) ?>" data-asal="<?= e(jsNum(jsRound($it['jamPerHari'] * 1000) / 1000)) ?>">
        <span class="text-ink2"><?= icon(ikonPeralatan($it['nama']), 20) ?></span>
        <div class="flex-1 min-w-0"><div class="font-semibold truncate"><?= e($it['nama']) ?></div><div class="text-[12px] text-ink3"><?= e(angka($it['jamPerHari'], 1)) ?> jam saat ini</div></div>
        <span class="hidden sm:block text-[12px] tabular-nums <?= $b['arah'] < 0 ? 'text-accent' : ($b['arah'] > 0 ? 'text-bad' : 'text-ink2') ?>" data-ke-wrap><?= e(angka($it['jamPerHari'], 1)) ?> → <span data-ke><?= e($b['ke']) ?></span></span>
        <?php partial('stepper', ['s_nama' => 'jam-' . $it['id'], 's_nilai' => jsRound($baru * 100) / 100, 's_min' => 0, 's_max' => 24, 's_satuan' => 'j', 's_data' => 'data-sim']); ?>
      </div>
      <?php endforeach; ?>
    <?php partial('kartu_close'); ?>
    <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
      <div class="flex items-start justify-between gap-3">
        <div><p class="text-[12px] text-ink3">Hasil simulasi</p><h2 class="judul-kartu mt-1">Potensi penghematan</h2></div>
        <span class="chip !text-[12px] !px-2.5 !py-1 tabular-nums" data-r="badge"><?= e($hasil['badge']) ?></span>
      </div>
      <div class="mt-5 border-t border-line">
        <div class="grid grid-cols-[1fr_auto_auto] gap-x-6 text-[12px] text-ink3 pt-4"><span></span><span>Sebelum</span><span class="text-right min-w-[84px]">Sesudah</span></div>
        <?php foreach ([['kwh', 'Konsumsi', 'zap', 'text-kwh', ' kWh'], ['biaya', 'Biaya', 'wallet', 'text-biaya', ''], ['emisi', 'Emisi', 'leaf', 'text-co2', '']] as $m): ?>
        <div class="py-4 border-b border-line last:border-b-0">
          <div class="flex items-center justify-between gap-3">
            <span class="flex items-center gap-1.5 text-[12px] <?= e($m[3]) ?>"><?= icon($m[2], 14) ?><?= e($m[1]) ?></span>
            <span class="text-[12px] text-ink2 tabular-nums"><span data-r="<?= e($m[0]) ?>-sebelum"><?= e($hasil[$m[0] . '-sebelum']) ?></span><?= e($m[4]) ?></span>
            <span class="text-ink2"><?= icon('arrow-right', 14) ?></span>
            <span class="text-[20px] font-medium tabular-nums"><span data-r="<?= e($m[0]) ?>-sesudah"><?= e($hasil[$m[0] . '-sesudah']) ?></span><?= e($m[4]) ?></span>
          </div>
          <p class="text-right text-[12px] font-semibold tabular-nums mt-1 <?= $hasil['arah'] > 0 ? 'text-bad' : 'text-accent' ?>" data-arah data-r="<?= e($m[0]) ?>-delta"><?= e($hasil[$m[0] . '-delta']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <button type="button" data-reset class="btn-sekunder w-full mt-2"><?= icon('rotate-ccw', 16) ?>Reset simulasi</button>
    <?php partial('kartu_close'); ?>
  </div>
<?php endif; ?>
<?php partial('app_close'); ?>
