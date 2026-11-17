<?php partial('head'); ?>
<main class="min-h-screen grid place-items-center px-5 py-10">
  <div class="w-full max-w-sm">
    <?php partial('empty', ['e_ikon' => 'shield-check', 'e_judul' => $kode . ' · ' . $judul, 'e_teks' => $pesan, 'e_href' => '/', 'e_label' => 'Kembali ke beranda']); ?>
  </div>
</main>
<?php partial('foot'); ?>
