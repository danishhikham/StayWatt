<?php partial('head'); ?>
<style>
  .lp a { text-decoration: none; }
  .lp-wrap { width: 100%; max-width: none; margin: 0 auto; padding: 0 clamp(20px, 5vw, 96px); }
  html { scroll-padding-top: 72px; }
  .lp-head { position: sticky; top: 0; z-index: 50; background: var(--bg); border-bottom: 1px solid var(--line); }
  .lp-nav { display: flex; align-items: center; justify-content: space-between; height: 64px; }
  .lp-logo { font-size: 18px; font-weight: 600; letter-spacing: -0.01em; color: var(--ink); }
  .lp-dot { display: inline-block; width: 6px; height: 6px; border-radius: 9999px; background: var(--accent); vertical-align: super; margin-left: 4px; }
  .lp-nav-r { display: flex; align-items: center; gap: 8px; }
  .lp-links { display: none; gap: 4px; }
  .lp-links a { padding: 8px 12px; border-radius: 8px; color: var(--ink2); font-size: 14px; }
  .lp-links a:hover { color: var(--ink); background: var(--surface); }
  @media (min-width: 720px) { .lp-links { display: flex; margin-right: 8px; } }
  html { scroll-behavior: smooth; }
  .lp-icon-btn { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 8px; color: var(--ink2); background: transparent; border: 0; cursor: pointer; }
  .lp-icon-btn:hover { color: var(--ink); background: var(--surface); }
  .lp-hero { display: grid; gap: 40px; padding: 56px 0 72px; align-items: center; }
  @media (min-width: 900px) { .lp-hero { grid-template-columns: 1.1fr 0.9fr; gap: clamp(40px, 6vw, 120px); min-height: calc(100vh - 64px); padding: 32px 0 72px; } }
  .lp-eyebrow { font-size: 12px; color: var(--accent); font-weight: 600; letter-spacing: .06em; text-transform: uppercase; }
  .lp-h1 { font-size: clamp(32px, 4.4vw, 72px); line-height: 1.08; font-weight: 600; letter-spacing: -0.02em; margin: 14px 0 18px; color: var(--ink); }
  .lp-lead { font-size: clamp(16px, 1.25vw, 20px); line-height: 1.65; color: var(--ink2); max-width: 640px; }
  .lp-cta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 28px; }
  .lp-note { margin-top: 16px; font-size: 12px; color: var(--ink3); }
  .lp-card { background: var(--surface); border-radius: 10px; padding: clamp(24px, 2vw, 36px); }
  .lp-mock-top { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--ink2); }
  .lp-big { display: flex; align-items: baseline; gap: 10px; margin-top: 20px; }
  .lp-big b { font-size: clamp(52px, 4.5vw, 76px); line-height: 1; font-weight: 500; font-variant-numeric: tabular-nums; }
  .lp-big span { font-size: 18px; color: var(--ink2); }
  .lp-trend { margin-top: 12px; font-size: 12px; font-weight: 500; color: var(--accent); display: inline-flex; align-items: center; gap: 4px; }
  .lp-rows { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); display: grid; gap: 14px; }
  .lp-row { display: flex; align-items: center; justify-content: space-between; font-size: 13px; }
  .lp-row .l { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--ink2); }
  .lp-row i { width: 8px; height: 8px; border-radius: 9999px; display: inline-block; }
  .lp-bars { margin-top: 22px; display: grid; gap: 14px; }
  .lp-bar-h { display: flex; justify-content: space-between; font-size: 13px; }
  .lp-bar-h span:last-child { font-size: 12px; color: var(--ink2); }
  .lp-bar-t { margin-top: 7px; height: 2px; border-radius: 9999px; background: var(--accent-dim); }
  .lp-bar-t div { height: 100%; border-radius: 9999px; background: var(--accent); }
  .lp-sec { padding: clamp(64px, 7vw, 112px) 0; border-top: 1px solid var(--line); }
  .lp-sec h2 { font-size: clamp(24px, 2.6vw, 40px); font-weight: 600; letter-spacing: -0.015em; color: var(--ink); }
  .lp-sec .sub { margin-top: 10px; color: var(--ink2); max-width: 640px; line-height: 1.6; }
  .lp-grid { display: grid; gap: clamp(16px, 1.6vw, 28px); margin-top: 36px; }
  @media (min-width: 640px) { .lp-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (min-width: 960px) { .lp-grid.c3 { grid-template-columns: repeat(3, 1fr); } }
  .lp-feat .ic { display: inline-grid; place-items: center; width: 40px; height: 40px; border-radius: 9999px; background: var(--surface2); color: var(--accent); margin-bottom: 16px; }
  .lp-feat h3 { font-size: 15px; font-weight: 600; color: var(--ink); }
  .lp-feat p { margin-top: 6px; color: var(--ink2); line-height: 1.6; }
  .lp-step .n { font-size: 12px; font-weight: 600; color: var(--accent); background: var(--accent-dim); border-radius: 6px; padding: 3px 8px; display: inline-block; }
  .lp-step h3 { margin-top: 14px; font-size: 15px; font-weight: 600; color: var(--ink); }
  .lp-step p { margin-top: 6px; color: var(--ink2); line-height: 1.6; }
  .lp-band { text-align: center; padding: 48px 24px; }
  .lp-band h2 { font-size: clamp(22px, 3vw, 30px); font-weight: 600; color: var(--ink); }
  .lp-band p { color: var(--ink2); margin: 10px auto 0; max-width: 460px; line-height: 1.6; }
  .lp-band .lp-cta { justify-content: center; }
  .lp-foot { border-top: 1px solid var(--line); padding: 28px 0 40px; display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; font-size: 12px; color: var(--ink3); }
</style>

<div class="lp">
  <header class="lp-head"><div class="lp-wrap lp-nav">
      <a href="<?= e(u('/')) ?>" class="lp-logo">StayWatt<span class="lp-dot"></span></a>
      <div class="lp-nav-r">
        <nav class="lp-links" aria-label="Bagian halaman"><a href="#fitur">Fitur</a><a href="#cara-kerja">Cara kerja</a></nav>
        <form method="post" action="<?= e(u('/tema')) ?>"><?php partial('csrf'); ?><button type="submit" class="lp-icon-btn" aria-label="<?= $tema === 'terang' ? 'Tema gelap' : 'Tema terang' ?>"><?= icon($tema === 'terang' ? 'moon' : 'sun', 18) ?></button></form>
        <a href="<?= e(u('/login')) ?>" class="btn-sekunder">Masuk</a>
        <a href="<?= e(u('/daftar')) ?>" class="btn-utama">Daftar</a>
      </div>
  </div></header>
  <div class="lp-wrap">

    <section class="lp-hero">
      <div>
        <p class="lp-eyebrow">Untuk penghuni kos</p>
        <h1 class="lp-h1">Tahu persis berapa listrik kosmu, sebelum tagihan datang.</h1>
        <p class="lp-lead">StayWatt menghitung estimasi konsumsi listrik, biaya, dan emisi CO₂e dari peralatan di kamarmu, lalu memberi saran penghematan yang bisa langsung dicoba.</p>
        <div class="lp-cta">
          <a href="<?= e(u('/daftar')) ?>" class="btn-utama">Mulai gratis <?= icon('arrow-right', 16) ?></a>
          <a href="<?= e(u('/login')) ?>" class="btn-sekunder">Saya sudah punya akun</a>
        </div>
        <p class="lp-note">Estimasi berdasarkan daya dan durasi pemakaian peralatan yang kamu catat.</p>
      </div>

      <div class="lp-card" aria-label="Contoh tampilan dashboard">
        <span class="lp-mock-top"><i style="width:8px;height:8px;border-radius:9999px;background:#D8B97A;display:inline-block"></i>Total konsumsi · contoh</span>
        <div class="lp-big"><b>177,3</b><span>kWh</span></div>
        <span class="lp-trend"><?= icon('arrow-down', 12) ?>3,1% dari bulan lalu</span>
        <div class="lp-rows">
          <div class="lp-row"><span class="l"><i style="background:#8FA8C8"></i>Estimasi biaya</span><b style="font-weight:500">Rp256.145</b></div>
          <div class="lp-row"><span class="l"><i style="background:#9DBF9E"></i>Emisi</span><b style="font-weight:500">154,25 kg CO₂e</b></div>
        </div>
        <div class="lp-bars">
          <div><div class="lp-bar-h"><span style="font-weight:600">AC ½ PK</span><span>Porsi terbesar</span></div><div class="lp-bar-t"><div style="width:62%"></div></div></div>
          <div><div class="lp-bar-h"><span style="font-weight:600">Kulkas mini</span><span>Menyala 24 jam</span></div><div class="lp-bar-t"><div style="width:28%"></div></div></div>
          <div><div class="lp-bar-h"><span style="font-weight:600">Laptop</span><span>6 jam/hari</span></div><div class="lp-bar-t"><div style="width:12%"></div></div></div>
        </div>
      </div>
    </section>

    <section class="lp-sec" id="fitur">
      <h2>Semua yang kamu butuhkan untuk berhemat</h2>
      <p class="sub">Catat peralatan sekali, StayWatt mengurus hitungannya.</p>
      <div class="lp-grid c3">
        <div class="lp-card lp-feat"><span class="ic"><?= icon('house', 20) ?></span><h3>Dashboard</h3><p>Ringkasan konsumsi, biaya, dan emisi bulanan, plus peralatan yang paling boros.</p></div>
        <div class="lp-card lp-feat"><span class="ic"><?= icon('settings', 20) ?></span><h3>Peralatan</h3><p>Catat daya, jumlah unit, dan durasi pemakaian. Estimasi muncul saat kamu mengetik.</p></div>
        <div class="lp-card lp-feat"><span class="ic"><?= icon('history', 20) ?></span><h3>Riwayat</h3><p>Bandingkan bulan ke bulan dan lihat rincian tiap peralatan.</p></div>
        <div class="lp-card lp-feat"><span class="ic"><?= icon('sparkles', 20) ?></span><h3>Rekomendasi</h3><p>Saran penghematan berbasis data peralatanmu, lengkap dengan potensi hemat.</p></div>
        <div class="lp-card lp-feat"><span class="ic"><?= icon('monitor-dot', 20) ?></span><h3>Simulasi</h3><p>Coba ubah kebiasaan tanpa risiko dan lihat dampaknya langsung, tanpa mengubah data asli.</p></div>
        <div class="lp-card lp-feat"><span class="ic"><?= icon('leaf', 20) ?></span><h3>Jejak karbon</h3><p>Setiap kWh dihitung juga emisi CO₂e-nya, supaya kamu tahu dampaknya.</p></div>
      </div>
    </section>

    <section class="lp-sec" id="cara-kerja">
      <h2>Cara kerjanya</h2>
      <div class="lp-grid c3">
        <div class="lp-card lp-step"><span class="n">1</span><h3>Buat akun</h3><p>Daftar dengan email, hanya butuh beberapa detik.</p></div>
        <div class="lp-card lp-step"><span class="n">2</span><h3>Catat peralatan</h3><p>Pilih dari daftar atau isi manual: daya, jumlah, dan jam pemakaian per hari.</p></div>
        <div class="lp-card lp-step"><span class="n">3</span><h3>Lihat dan hemat</h3><p>Pantau dashboard, ikuti rekomendasi, dan coba simulasinya.</p></div>
      </div>
    </section>

    <section class="lp-sec">
      <div class="lp-card lp-band">
        <h2>Siap mengendalikan listrik kosmu?</h2>
        <p>Mulai catat peralatanmu dan lihat estimasinya sekarang.</p>
        <div class="lp-cta">
          <a href="<?= e(u('/daftar')) ?>" class="btn-utama">Buat akun</a>
          <a href="<?= e(u('/login')) ?>" class="btn-sekunder">Masuk</a>
        </div>
      </div>
    </section>

    <footer class="lp-foot">
      <span>StayWatt · Estimasi konsumsi listrik, biaya, dan emisi CO₂e untuk penghuni kos.</span>
      <span>Hasil berupa estimasi, bukan jaminan penghematan.</span>
    </footer>
  </div>
</div>
<?php partial('foot'); ?>