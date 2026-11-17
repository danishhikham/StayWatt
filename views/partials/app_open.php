<?php partial('head'); ?>
<?php
  $itemNav = [
    ['k' => 'dashboard', 'h' => '/', 'l' => 'Dashboard', 'i' => 'house'],
    ['k' => 'peralatan', 'h' => '/peralatan', 'l' => 'Peralatan', 'i' => 'settings'],
    ['k' => 'riwayat', 'h' => '/riwayat', 'l' => 'Riwayat', 'i' => 'history'],
    ['k' => 'rekomendasi', 'h' => '/rekomendasi', 'l' => 'Rekomendasi', 'i' => 'sparkles'],
    ['k' => 'simulasi', 'h' => '/simulasi', 'l' => 'Simulasi', 'i' => 'monitor-dot'],
  ];
  if ($user['role'] === 'admin') $itemNav[] = ['k' => 'admin', 'h' => '/admin', 'l' => 'Admin', 'i' => 'sliders-horizontal'];
?>
<aside class="hidden md:flex fixed inset-y-0 left-0 w-[232px] flex-col border-r border-line px-3 py-6 bg-bg">
  <a href="<?= e(u('/')) ?>" class="px-3 mb-9 text-[18px] font-semibold tracking-tight">StayWatt<span class="inline-block w-1.5 h-1.5 rounded-full bg-accent align-super ml-1"></span></a>
  <nav class="flex flex-col gap-1.5 flex-1" aria-label="Utama">
    <?php foreach ($itemNav as $n): $a = $halaman === $n['k']; ?>
    <a href="<?= e(u($n['h'])) ?>" class="nav-item"<?= $a ? ' aria-current="page"' : '' ?>>
      <?php if ($a): ?><span class="absolute -left-3 top-1/2 -translate-y-1/2 w-[2px] h-5 bg-accent"></span><?php endif; ?>
      <?= icon($n['i'], 18) ?><span><?= e($n['l']) ?></span>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="flex items-center gap-3 px-2 pt-4 border-t border-line">
    <span class="w-8 h-8 rounded-full bg-surface2 text-ink2 grid place-items-center text-[12px] font-semibold"><?= e(mb_strtoupper(mb_substr($user['nama'], 0, 1))) ?></span>
    <div class="leading-tight min-w-0"><div class="font-semibold truncate"><?= e($user['nama']) ?></div><div class="text-[12px] text-ink2"><?= $user['role'] === 'admin' ? 'Admin' : 'Pengguna' ?></div></div>
    <?php partial('menu_pengguna', ['panel' => 'bottom-full mb-2 right-0']); ?>
  </div>
</aside>
<header class="md:hidden flex items-center justify-between px-5 h-14 border-b border-line">
  <a href="<?= e(u('/')) ?>" class="text-[18px] font-semibold tracking-tight">StayWatt<span class="inline-block w-1.5 h-1.5 rounded-full bg-accent align-super ml-1"></span></a>
  <?php partial('menu_pengguna', ['panel' => 'top-full mt-2 right-0']); ?>
</header>
<nav class="md:hidden fixed bottom-0 inset-x-0 z-20 flex justify-around bg-surface border-t border-line h-16" aria-label="Utama">
  <?php foreach ($itemNav as $n): $a = $halaman === $n['k']; ?>
  <a href="<?= e(u($n['h'])) ?>" class="flex flex-col items-center justify-center gap-1 flex-1 text-[11px] <?= $a ? 'text-accent' : 'text-ink2' ?>"<?= $a ? ' aria-current="page"' : '' ?>><?= icon($n['i'], 20) ?><?= e($n['l']) ?></a>
  <?php endforeach; ?>
</nav>
<main class="main-app md:ml-[232px] px-5 md:px-9 py-8 md:py-9 pb-24 md:pb-9">
<div class="wadah-app">