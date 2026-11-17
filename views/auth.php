<?php partial('head'); ?>
<main class="min-h-screen grid place-items-center px-5 py-10">
  <div class="w-full max-w-sm">
    <div class="flex items-center justify-between mb-8">
      <span class="text-[18px] font-semibold tracking-tight">StayWatt<span class="inline-block w-1.5 h-1.5 rounded-full bg-accent align-super ml-1"></span></span>
      <div class="w-40"><?php partial('toggle_tema'); ?></div>
    </div>
    <?php partial('kartu_open', ['pad' => 'p-6', 'kelas' => '']); ?>
      <h1 class="judul-halaman"><?= $mode === 'masuk' ? 'Masuk' : 'Buat akun' ?></h1>
      <p class="text-ink2 mt-1 mb-6"><?= $mode === 'masuk' ? 'Lanjutkan memantau listrik kosmu.' : 'Mulai pantau listrik, biaya, dan emisi kosmu.' ?></p>
      <form method="post" action="<?= e(u($mode === 'masuk' ? '/login' : '/daftar')) ?>" class="flex flex-col gap-4" novalidate>
        <?php partial('csrf'); ?>
        <?php if (!empty($errors['umum'])): ?><p class="pesan-err !mt-0"><?= e($errors['umum']) ?></p><?php endif; ?>
        <?php if ($mode === 'daftar'): ?><?php partial('field', ['nama' => 'nama', 'label' => 'Nama', 'tipe' => 'text', 'nilai' => $v['nama'] ?? '', 'galat' => $errors['nama'] ?? null, 'attrs' => 'autocomplete="name"']); ?><?php endif; ?>
        <?php partial('field', ['nama' => 'email', 'label' => 'Email', 'tipe' => 'email', 'nilai' => $v['email'] ?? '', 'galat' => $errors['email'] ?? null, 'attrs' => 'autocomplete="email"']); ?>
        <?php partial('field', ['nama' => 'password', 'label' => 'Kata sandi', 'tipe' => 'password', 'nilai' => '', 'galat' => $errors['password'] ?? null, 'attrs' => 'autocomplete="' . ($mode === 'masuk' ? 'current-password' : 'new-password') . '"']); ?>
        <button type="submit" class="btn-utama mt-2"><?= $mode === 'masuk' ? 'Masuk' : 'Daftar' ?></button>
      </form>
    <?php partial('kartu_close'); ?>
    <p class="mt-5 text-center text-ink2"><?= $mode === 'masuk' ? 'Belum punya akun? <a class="text-accent font-semibold" href="' . e(u('/daftar')) . '">Daftar</a>' : 'Sudah punya akun? <a class="text-accent font-semibold" href="' . e(u('/login')) . '">Masuk</a>' ?></p>
  </div>
</main>
<?php partial('foot'); ?>
