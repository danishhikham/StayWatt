<?php partial('app_open', ['halaman' => 'peralatan']); ?>
<div class="flex items-start justify-between gap-4 mb-6">
  <div>
    <p class="eyebrow"><?= e(labelPeriode($periode)) ?></p>
    <h1 class="judul-halaman">Peralatan Saya</h1>
    <p class="text-ink2 mt-1"><?= count($items) ?> peralatan · <?= e(angka($total, 1)) ?> kWh/bln</p>
  </div>
  <a href="<?= e(u('/peralatan/baru')) ?>" class="btn-utama"><?= icon('plus', 16) ?>Tambah peralatan</a>
</div>
<?php if (!count($items)): ?>
  <?php partial('empty', ['e_ikon' => 'settings', 'e_judul' => 'Belum ada peralatan', 'e_teks' => 'Tambahkan peralatan pertamamu untuk mulai menghitung estimasi.', 'e_href' => '/peralatan/baru', 'e_label' => 'Tambah peralatan']); ?>
<?php else: ?>
  <?php partial('kartu_open', ['pad' => 'p-2', 'kelas' => 'overflow-x-auto']); ?>
    <table class="tbl min-w-[640px]">
      <thead><tr><th>Nama peralatan</th><th>Kategori</th><th class="!text-right">Penggunaan</th><th class="!text-right">Daya</th><th class="!text-right">Konsumsi</th><th class="w-20"><span class="sr-only">Aksi</span></th></tr></thead>
      <tbody>
        <?php foreach ($tampil as $i): ?>
        <tr>
          <td class="font-semibold"><?= e($i['nama']) ?></td>
          <td><?php partial('chip', ['teks' => $i['kategori']]); ?></td>
          <td class="kanan text-ink2"><?= e($i['jumlah']) ?> unit · <?= e(angka($i['jamPerHari'], 1)) ?> jam/hari</td>
          <td class="kanan text-ink2"><?= e(angka($i['dayaW'])) ?> W</td>
          <td class="kanan font-semibold"><?= e(angka($i['kwh'], 1)) ?> kWh/bln</td>
          <td><span class="flex justify-end gap-3 text-ink3">
            <a href="<?= e(u('/peralatan/' . $i['id'] . '/ubah')) ?>" class="hover:text-ink transition-colors" aria-label="Ubah <?= e($i['nama']) ?>"><?= icon('pencil', 16) ?></a>
            <a href="<?= e(u('/peralatan/' . $i['id'] . '/hapus')) ?>" class="hover:text-ink transition-colors" aria-label="Hapus <?= e($i['nama']) ?>"><?= icon('trash-2', 16) ?></a>
          </span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php partial('kartu_close'); ?>
  <?php if (count($items) > $per): ?><?php partial('pager', ['p_total' => count($items), 'p_page' => $page, 'p_per' => $per, 'p_ekstra' => []]); ?><?php endif; ?>
<?php endif; ?>
<?php if (($modal ?? null) === 'form'): ?><?php partial('form_peralatan'); ?><?php endif; ?>
<?php if (($modal ?? null) === 'hapus'): ?><?php partial('konfirmasi', ['k_judul' => 'Hapus peralatan?', 'k_teks' => $hapus['nama'] . ' beserta riwayat pemakaiannya akan dihapus.', 'k_aksi' => '/peralatan/' . $hapus['id'] . '/hapus', 'k_batal' => '/peralatan']); ?><?php endif; ?>
<?php partial('app_close'); ?>
