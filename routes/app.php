<?php
$router = new Router();

function uid(): int
{
    return (int)$_SESSION['user']['id'];
}

// ---------- Dashboard ----------
$router->get('/', function () {
    $rekap = rekap(uid());
    $namaDepan = explode(' ', $_SESSION['user']['nama'])[0];
    if (!$rekap) render('dashboard', ['judul' => 'Dashboard', 'namaDepan' => $namaDepan, 'kosong' => true, 'scripts' => []]);
    $idx = -1;
    $pilih = qparam('periode');
    foreach ($rekap as $i => $b) {
        if ($b['periode'] === $pilih) { $idx = $i; break; }
    }
    if ($idx < 0) $idx = count($rekap) - 1;
    $cur = $rekap[$idx];
    $prev = $idx > 0 ? $rekap[$idx - 1] : null;
    $awal = max(0, $idx - 5);
    $grafik = array_slice($rekap, $awal, $idx + 1 - $awal);
    $top = $cur['items'][0] ?? null;
    $porsi = $top ? $top['porsi'] : 0;
    $waktu = $idx === count($rekap) - 1 ? 'bulan ini' : 'pada ' . labelPeriode($cur['periode']);
    $frasa = $porsi >= 50 ? 'lebih dari separuh' : ($porsi >= 40 ? 'hampir separuh' : angka($porsi, 1) . '%');
    $opsi = [];
    foreach (array_reverse($rekap) as $b) $opsi[] = ['v' => $b['periode'], 'l' => labelPeriode($b['periode'])];
    $terboros = [];
    foreach (array_slice($cur['items'], 0, 5) as $i) {
        $terboros[] = ['label' => $i['nama'], 'teks' => angka($i['kwh'], 1) . ' kWh · ' . angka($i['porsi'], 1) . '%', 'pct' => jsRound($i['porsi'] * 10) / 10];
    }
    render('dashboard', [
        'judul' => 'Dashboard', 'kosong' => false, 'namaDepan' => $namaDepan, 'cur' => $cur, 'aktif' => $idx === count($rekap) - 1,
        'pct' => $prev ? persenPerubahan($cur['total']['kwh'], $prev['total']['kwh']) : null,
        'prevNama' => $prev ? namaBulan($prev['periode']) : null,
        'opsi' => $opsi,
        'grafik' => [
            'label' => array_map(function ($b) { return bulanPendek($b['periode']); }, $grafik),
            'nilai' => array_map(function ($b) { return jsRound($b['total']['kwh'] * 10) / 10; }, $grafik),
        ],
        'kalimat' => $top ? "{$top['nama']} menyumbang $frasa konsumsimu $waktu." : '',
        'terboros' => $terboros,
        'scripts' => ['/vendor/chart.umd.js', '/js/dashboard.js'],
    ]);
});

// ---------- Peralatan ----------
function muatPeralatan(int $userId): array
{
    $rekap = rekap($userId);
    $aktif = $rekap ? $rekap[count($rekap) - 1] : null;
    $rows = q('SELECT p.id, p.nama, p.daya_w, p.jumlah, p.kategori_id, k.nama AS kategori FROM peralatan p JOIN kategori_peralatan k ON k.id = p.kategori_id WHERE p.user_id = ? ORDER BY p.id', [$userId]);
    $peta = [];
    if ($aktif) {
        foreach ($aktif['items'] as $i) $peta[$i['id']] = $i;
    }
    $items = [];
    foreach ($rows as $r) {
        $items[] = $peta[(int)$r['id']] ?? [
            'id' => (int)$r['id'], 'nama' => $r['nama'], 'kategori' => $r['kategori'], 'kategoriId' => (int)$r['kategori_id'],
            'dayaW' => (int)$r['daya_w'], 'jumlah' => (int)$r['jumlah'], 'jamPerHari' => 0, 'kwh' => 0, 'biaya' => 0, 'emisi' => 0, 'porsi' => 0,
        ];
    }
    usort($items, function ($a, $b) {
        return ($b['kwh'] <=> $a['kwh']) ?: ($a['id'] <=> $b['id']);
    });
    return ['items' => $items, 'periode' => periodeAktif($rekap), 'total' => $aktif ? $aktif['total']['kwh'] : 0];
}

function renderPeralatan(array $extra = [], int $status = 200): void
{
    $base = muatPeralatan(uid());
    $per = 10;
    $total = count($base['items']);
    $page = min(max(1, parseIntJs(qparam('page')) ?: 1), max(1, (int)ceil($total / $per)));
    $kategori = q('SELECT id, nama FROM kategori_peralatan ORDER BY id');
    render('peralatan', array_merge(
        ['judul' => 'Peralatan'],
        $base,
        ['tampil' => array_slice($base['items'], ($page - 1) * $per, $per), 'page' => $page, 'per' => $per, 'kategori' => $kategori, 'scripts' => []],
        $extra
    ), $status);
}

function estimasiForm(int $userId, array $v): array
{
    $cfg = getKonfig();
    $rekap = rekap($userId);
    $hari = hariDalamBulan(periodeAktif($rekap));
    $e = estimasi([
        'dayaW' => (float)($v['daya_w'] ?: 0), 'jamPerHari' => (float)($v['jam'] ?: 0), 'hari' => $hari, 'jumlah' => (float)($v['jumlah'] ?: 0),
    ], $cfg);
    return ['kwh' => angka($e['kwh'], 1) . ' kWh/bln', 'biaya' => rupiah($e['biaya']), 'emisi' => angka($e['emisi'], 2) . " kg CO\u{2082}e"];
}

function validasiPeralatan(): array
{
    $jamTeks = trim(str_replace(',', '.', fparam('jam')));
    $jam = $jamTeks === '' ? 0.0 : (is_numeric($jamTeks) ? (float)$jamTeks : null);
    $v = [
        'nama' => trim(fparam('nama')), 'kategori_id' => parseIntJs(fparam('kategori_id')), 'daya_w' => parseIntJs(fparam('daya_w')),
        'jumlah' => parseIntJs(fparam('jumlah')), 'jam' => $jam,
    ];
    $e = [];
    if ($v['nama'] === '' || mb_strlen($v['nama']) > 100) $e['nama'] = 'Nama peralatan wajib diisi (maks. 100 karakter).';
    if (!($v['kategori_id'] !== null && $v['kategori_id'] > 0)) $e['kategori_id'] = 'Pilih kategori.';
    if (!antara($v['daya_w'], 1, 10000)) $e['daya_w'] = "Daya harus 1\u{2013}10.000 W.";
    if (!antara($v['jumlah'], 1, 99)) $e['jumlah'] = "Jumlah unit harus 1\u{2013}99.";
    if (!antara($v['jam'], 0, 24)) $e['jam'] = "Durasi harus 0\u{2013}24 jam per hari.";
    if (!isset($e['kategori_id']) && !q('SELECT id FROM kategori_peralatan WHERE id = ?', [$v['kategori_id']])) $e['kategori_id'] = 'Kategori tidak ditemukan.';
    return [$v, $e];
}

function tampilFormPeralatan($id, array $v, array $errors = [], int $status = 200): void
{
    $kategori = q('SELECT id, nama FROM kategori_peralatan ORDER BY id');
    $referensi = q('SELECT r.id, r.nama, r.daya_default_w, r.kategori_id FROM referensi_peralatan r ORDER BY r.nama');
    renderPeralatan([
        'modal' => 'form', 'form' => ['id' => $id, 'v' => $v, 'errors' => $errors], 'est' => estimasiForm(uid(), $v),
        'referensi' => $referensi, 'kategori' => $kategori, 'scripts' => ['/js/stepper.js', '/js/peralatan-form.js'],
    ], $status);
}

function simpanPeralatan($id, array $v): void
{
    $uid = uid();
    $periode = periodeAktif(rekap($uid));
    $pdo = db();
    $pdo->beginTransaction();
    try {
        if ($id === null) {
            $pdo->prepare('INSERT INTO peralatan (user_id, kategori_id, nama, daya_w, jumlah) VALUES (?,?,?,?,?)')
                ->execute([$uid, $v['kategori_id'], $v['nama'], $v['daya_w'], $v['jumlah']]);
            $id = (int)$pdo->lastInsertId();
        } else {
            $pdo->prepare('UPDATE peralatan SET kategori_id = ?, nama = ?, daya_w = ?, jumlah = ? WHERE id = ? AND user_id = ?')
                ->execute([$v['kategori_id'], $v['nama'], $v['daya_w'], $v['jumlah'], $id, $uid]);
        }
        isiPemakaianBulan($pdo, (int)$id, $periode, (float)$v['jam']);
        $pdo->commit();
    } catch (Throwable $err) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $err;
    }
    Cache::clearUser($uid);
}

$router->get('/peralatan', function () {
    renderPeralatan();
});
$router->get('/peralatan/baru', function () {
    tampilFormPeralatan(null, ['nama' => '', 'kategori_id' => '', 'daya_w' => '', 'jumlah' => 1, 'jam' => 1]);
});

$router->get('/peralatan/:id/ubah', function (array $p) {
    $items = muatPeralatan(uid())['items'];
    $it = null;
    foreach ($items as $i) {
        if ($i['id'] === (int)$p['id']) { $it = $i; break; }
    }
    if (!$it) redirect('/peralatan');
    tampilFormPeralatan($it['id'], [
        'nama' => $it['nama'], 'kategori_id' => $it['kategoriId'], 'daya_w' => $it['dayaW'], 'jumlah' => $it['jumlah'], 'jam' => jsRound($it['jamPerHari'] * 100) / 100,
    ]);
});

$router->post('/peralatan', function () {
    [$v, $e] = validasiPeralatan();
    if ($e) tampilFormPeralatan(null, $v, $e, 400);
    simpanPeralatan(null, $v);
    redirect('/peralatan');
});

$router->post('/peralatan/:id', function (array $p) {
    $id = (int)$p['id'];
    if (!q('SELECT id FROM peralatan WHERE id = ? AND user_id = ?', [$id, uid()])) redirect('/peralatan');
    [$v, $e] = validasiPeralatan();
    if ($e) tampilFormPeralatan($id, $v, $e, 400);
    simpanPeralatan($id, $v);
    redirect('/peralatan');
});

$router->get('/peralatan/:id/hapus', function (array $p) {
    $items = muatPeralatan(uid())['items'];
    $it = null;
    foreach ($items as $i) {
        if ($i['id'] === (int)$p['id']) { $it = $i; break; }
    }
    if (!$it) redirect('/peralatan');
    renderPeralatan(['modal' => 'hapus', 'hapus' => $it]);
});
$router->post('/peralatan/:id/hapus', function (array $p) {
    eksekusi('DELETE FROM peralatan WHERE id = ? AND user_id = ?', [(int)$p['id'], uid()]);
    Cache::clearUser(uid());
    redirect('/peralatan');
});

// ---------- Riwayat ----------
$router->get('/riwayat', function () {
    $rekap = rekap(uid());
    $r = qnum('rentang');
    $rentang = ($r !== null && in_array($r, [3.0, 6.0, 12.0], true)) ? (int)$r : 6;
    $pr = qnum('per');
    $per = ($pr !== null && in_array($pr, [10.0, 25.0, 50.0], true)) ? (int)$pr : 10;
    $semua = [];
    foreach ($rekap as $i => $b) {
        $b['pct'] = $i > 0 ? persenPerubahan($b['total']['kwh'], $rekap[$i - 1]['total']['kwh']) : null;
        $semua[] = $b;
    }
    $semua = array_slice(array_reverse($semua), 0, $rentang);
    $page = min(max(1, parseIntJs(qparam('page')) ?: 1), max(1, (int)ceil(count($semua) / $per)));
    render('riwayat', [
        'judul' => 'Riwayat', 'baris' => array_slice($semua, ($page - 1) * $per, $per), 'total' => count($semua),
        'rentang' => $rentang, 'per' => $per, 'page' => $page, 'scripts' => [],
    ]);
});

// ---------- Rekomendasi ----------
$router->get('/rekomendasi', function () {
    $rekap = rekap(uid());
    $cfg = getKonfig();
    $bulan = $rekap ? $rekap[count($rekap) - 1] : null;
    render('rekomendasi', ['judul' => 'Rekomendasi', 'rek' => buatRekomendasi($bulan, $cfg), 'scripts' => []]);
});

// ---------- Simulasi ----------
$router->get('/simulasi', function () {
    $rekap = rekap(uid());
    $cfg = getKonfig();
    $bulan = $rekap ? $rekap[count($rekap) - 1] : null;
    if (!$bulan) render('simulasi', ['judul' => 'Simulasi', 'bulan' => null, 'scripts' => []]);
    $perubahan = simUrai(qparam('d'));
    render('simulasi', [
        'judul' => 'Simulasi', 'bulan' => $bulan, 'perubahan' => $perubahan,
        'hasil' => simTampil(simJalankan($bulan, $cfg, $perubahan)), 'scripts' => ['/js/stepper.js', '/js/simulasi.js'],
    ]);
});

// ---------- API kecil (memakai modul perhitungan yang sama) ----------
$router->get('/api/estimasi', function () {
    jsonOut(estimasiForm(uid(), [
        'daya_w' => batas(qparam('daya'), 0, 10000), 'jumlah' => batas(qparam('jumlah'), 0, 99), 'jam' => batas(qparam('jam'), 0, 24),
    ]));
});
$router->get('/api/simulasi', function () {
    $rekap = rekap(uid());
    $cfg = getKonfig();
    if (!$rekap) jsonOut([]);
    jsonOut(simTampil(simJalankan($rekap[count($rekap) - 1], $cfg, simUrai(qparam('d')))));
});

return $router;
