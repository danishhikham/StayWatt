<?php partial('app_open', ['halaman' => 'rekomendasi']); ?>
<div class="mb-6">
  <p class="eyebrow">Saran berbasis data</p>
  <h1 class="judul-halaman">Rekomendasi Penghematan</h1>
  <p class="text-ink2 mt-1">Berdasarkan aturan dari data peralatanmu. Ini estimasi, bukan jaminan penghematan.</p>
</div>
<?php if (!count($rek['daftar'])): ?>
  <?php partial('empty', ['e_ikon' => 'sparkles', 'e_judul' => 'Belum ada saran', 'e_teks' => 'Tambahkan peralatan dan durasi pemakaian agar saran bisa dihitung.', 'e_href' => '/peralatan/baru', 'e_label' => 'Tambah peralatan']); ?>
<?php else: ?>
  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => 'flex items-start justify-between gap-4']); ?>
    <div class="flex gap-3">
      <span class="text-ink2 mt-0.5"><?= icon('leaf', 22) ?></span>
      <div>
        <p class="text-[12px] text-ink2">Potensi hemat bulan ini</p>
        <p class="text-[16px] font-semibold tabular-nums mt-1"><?= e(angka($rek['total']['kwh'], 1)) ?> kWh · <?= e(rupiah($rek['total']['biaya'])) ?> · <?= e(angka($rek['total']['emisi'], 2)) ?> kg CO₂e</p>
        <p class="text-[12px] text-accent mt-1.5">Setara <?= e(angka($rek['total']['persen'], 1)) ?>% dari konsumsi bulan ini</p>
      </div>
    </div>
    <span class="chip !text-[13px] !px-3 !py-1.5 tabular-nums">−<?= e(angka($rek['total']['persen'], 1)) ?>%</span>
  <?php partial('kartu_close'); ?>
  <ul class="mt-4 flex flex-col">
    <?php foreach ($rek['daftar'] as $i => $r): ?>
    <li class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-6 rounded-card <?= $i === 0 ? 'bg-surface' : 'border-b border-line rounded-none' ?>">
      <div class="min-w-0">
        <h2 class="font-semibold"><?= e($r['nama']) ?></h2>
        <p class="text-ink2 mt-2"><?= e($r['teks']) ?></p>
      </div>
      <div class="flex items-center gap-4 shrink-0">
        <div class="flex flex-col gap-2 w-44">
          <?php partial('baris_metrik', ['dot' => 'kwh', 'label' => '', 'nilai' => angka($r['kwh'], 1) . ' kWh']); ?>
          <?php partial('baris_metrik', ['dot' => 'biaya', 'label' => '', 'nilai' => rupiah($r['biaya'])]); ?>
          <?php partial('baris_metrik', ['dot' => 'co2', 'label' => '', 'nilai' => angka($r['emisi'], 2) . ' kg CO₂e']); ?>
        </div>
        <a href="<?= e(u('/simulasi') . '?d=' . $r['id'] . ':' . jsNum(jsRound($r['jamBaru'] * 100) / 100)) ?>" class="btn-sekunder">Coba <?= icon('arrow-right', 16) ?></a>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php partial('app_close'); ?>
