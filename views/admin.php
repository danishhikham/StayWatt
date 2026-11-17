<?php partial('app_open', ['halaman' => 'admin']); ?>
<div class="mb-6">
  <p class="eyebrow">Panel admin</p>
  <h1 class="judul-halaman">Admin</h1>
  <p class="text-ink2 mt-1">Kelola tarif, faktor emisi, dan data referensi peralatan.</p>
</div>
<div class="flex flex-col gap-4">
  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
    <h2 class="judul-kartu">Tarif & faktor emisi</h2>
    <p class="text-[12px] text-ink3 mt-1">Berlaku saat ini: Rp<?= e(angka($konf['tarif'], 2)) ?>/kWh · <?= e(str_replace('.', ',', (string)$konf['faktor'])) ?> kg CO₂e/kWh · <?= e($konf['sumber']) ?></p>
    <form method="post" action="<?= e(u('/admin/konfigurasi')) ?>" class="mt-5 grid sm:grid-cols-2 gap-4" novalidate>
      <?php partial('csrf'); ?>
      <?php partial('field', ['nama' => 'tarif', 'label' => 'Tarif per kWh (Rp)', 'tipe' => 'text', 'nilai' => $vCfg ? $vCfg['tarif'] : angka($konf['tarif'], 2), 'galat' => $errCfg['tarif'] ?? null, 'attrs' => 'inputmode="decimal"']); ?>
      <?php partial('field', ['nama' => 'faktor', 'label' => 'Faktor emisi (kg CO₂e/kWh)', 'tipe' => 'text', 'nilai' => $vCfg ? $vCfg['faktor'] : str_replace('.', ',', (string)$konf['faktor']), 'galat' => $errCfg['faktor'] ?? null, 'attrs' => 'inputmode="decimal"']); ?>
      <?php partial('field', ['nama' => 'sumber', 'label' => 'Sumber', 'tipe' => 'text', 'nilai' => $vCfg ? $vCfg['sumber'] : $konf['sumber'], 'galat' => $errCfg['sumber'] ?? null, 'attrs' => 'maxlength="255"']); ?>
      <?php partial('field', ['nama' => 'berlaku', 'label' => 'Berlaku mulai', 'tipe' => 'date', 'nilai' => $vCfg ? $vCfg['berlaku'] : date('Y-m-d'), 'galat' => $errCfg['berlaku'] ?? null, 'attrs' => '']); ?>
      <div class="sm:col-span-2 flex justify-end"><button type="submit" class="btn-utama">Simpan versi baru</button></div>
    </form>
    <table class="tbl mt-4">
      <thead><tr><th class="!px-0">Berlaku mulai</th><th class="!text-right">Tarif</th><th class="!text-right">Faktor emisi</th><th class="!pl-8">Sumber</th></tr></thead>
      <tbody>
        <?php foreach ($cfg as $c): ?><tr><td class="!px-0 !py-3"><?= e($c['berlaku_mulai']) ?></td><td class="kanan !py-3">Rp<?= e(angka((float)$c['tarif_per_kwh'], 2)) ?></td><td class="kanan !py-3"><?= e(str_replace('.', ',', (string)(float)$c['faktor_emisi'])) ?></td><td class="!py-3 !pl-8 text-ink2"><?= e($c['sumber']) ?></td></tr><?php endforeach; ?>
      </tbody>
    </table>
  <?php partial('kartu_close'); ?>

  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
    <div class="flex items-center justify-between gap-3">
      <h2 class="judul-kartu">Data referensi peralatan</h2>
      <a href="<?= e(u('/admin/referensi/baru')) ?>" class="btn-utama"><?= icon('plus', 16) ?>Tambah referensi</a>
    </div>
    <div class="overflow-x-auto mt-3">
      <table class="tbl min-w-[480px]">
        <thead><tr><th class="!px-0">Nama</th><th>Kategori</th><th class="!text-right">Daya bawaan</th><th class="w-20"><span class="sr-only">Aksi</span></th></tr></thead>
        <tbody>
          <?php foreach ($ref as $r): ?>
          <tr>
            <td class="!px-0 font-semibold"><?= e($r['nama']) ?></td>
            <td><?php partial('chip', ['teks' => $r['kategori']]); ?></td>
            <td class="kanan"><?= e(angka($r['daya_default_w'])) ?> W</td>
            <td><span class="flex justify-end gap-3 text-ink3">
              <a href="<?= e(u('/admin/referensi/' . $r['id'] . '/ubah')) ?>" class="hover:text-ink transition-colors" aria-label="Ubah <?= e($r['nama']) ?>"><?= icon('pencil', 16) ?></a>
              <a href="<?= e(u('/admin/referensi/' . $r['id'] . '/hapus')) ?>" class="hover:text-ink transition-colors" aria-label="Hapus <?= e($r['nama']) ?>"><?= icon('trash-2', 16) ?></a>
            </span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php partial('pager', ['p_total' => $refTotal, 'p_page' => $page, 'p_per' => $per, 'p_ekstra' => []]); ?>
  <?php partial('kartu_close'); ?>

  <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
    <h2 class="judul-kartu">Ringkasan agregat</h2>
    <p class="text-[12px] text-ink3 mt-1">Gabungan seluruh pengguna<?= $agregat['bulan'] ? ' · ' . e(labelPeriode($agregat['bulan'])) : '' ?>. Tanpa data pribadi.</p>
    <div class="mt-5 grid sm:grid-cols-2 gap-x-10 gap-y-4">
      <div class="flex flex-col gap-4">
        <?php partial('baris_metrik', ['dot' => 'kwh', 'label' => 'Total konsumsi', 'nilai' => angka($agregat['totalKwh'], 1), 'satuan' => 'kWh']); ?>
        <?php partial('baris_metrik', ['dot' => 'biaya', 'label' => 'Estimasi biaya', 'nilai' => rupiah($agregat['biaya'])]); ?>
        <?php partial('baris_metrik', ['dot' => 'co2', 'label' => 'Emisi', 'nilai' => angka($agregat['emisi'], 2), 'satuan' => 'kg CO₂e']); ?>
        <?php partial('baris_metrik', ['dot' => 'kwh', 'label' => 'Rata-rata per pengguna aktif', 'nilai' => angka($agregat['rata'], 1), 'satuan' => 'kWh']); ?>
        <?php partial('baris_metrik', ['dot' => 'biaya', 'label' => 'Pengguna terdaftar', 'nilai' => angka($agregat['pengguna']), 'satuan' => '(' . angka($agregat['aktif']) . ' aktif)']); ?>
        <?php partial('baris_metrik', ['dot' => 'co2', 'label' => 'Peralatan tercatat', 'nilai' => angka($agregat['peralatan'])]); ?>
      </div>
      <div>
        <p class="text-[12px] text-ink3 mb-4">Porsi per kategori</p>
        <?php if (count($agregat['bar'])): ?><?php partial('bar_list', ['items' => $agregat['bar']]); ?><?php else: ?><p class="text-ink2">Belum ada data pemakaian.</p><?php endif; ?>
      </div>
    </div>
  <?php partial('kartu_close'); ?>
</div>
<?php if (($modal ?? null) === 'ref'): ?><?php partial('form_referensi'); ?><?php endif; ?>
<?php if (($modal ?? null) === 'hapus-ref'): ?><?php partial('konfirmasi', ['k_judul' => 'Hapus referensi?', 'k_teks' => $hapus['nama'] . ' akan dihapus dari daftar referensi.', 'k_aksi' => '/admin/referensi/' . $hapus['id'] . '/hapus', 'k_batal' => '/admin']); ?><?php endif; ?>
<?php partial('app_close'); ?>
