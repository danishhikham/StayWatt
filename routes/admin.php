<?php
// Dipasang di /admin (sudah melewati masuk + hanyaAdmin di server.php).
$router = new Router();

function renderAdmin(array $extra = [], int $status = 200): void
{
    $cfg = q('SELECT id, faktor_emisi, tarif_per_kwh, sumber, berlaku_mulai FROM konfigurasi_emisi_tarif ORDER BY berlaku_mulai DESC, id DESC LIMIT 6');
    $kategori = q('SELECT id, nama FROM kategori_peralatan ORDER BY nama');
    $ref = q('SELECT r.id, r.nama, r.daya_default_w, r.kategori_id, k.nama AS kategori FROM referensi_peralatan r JOIN kategori_peralatan k ON k.id = r.kategori_id ORDER BY r.nama');
    $pengguna = (int)q("SELECT COUNT(*) AS pengguna FROM users WHERE role = 'pengguna'")[0]['pengguna'];
    $peralatan = (int)q('SELECT COUNT(*) AS peralatan FROM peralatan')[0]['peralatan'];
    $aktif = (int)q('SELECT COUNT(DISTINCT user_id) AS aktif FROM peralatan')[0]['aktif'];
    $kat = q("SELECT DATE_FORMAT(u.tanggal,'%Y-%m') AS bulan, k.nama AS kategori, SUM(p.daya_w * p.jumlah * u.durasi_jam) / 1000 AS kwh FROM pemakaian u JOIN peralatan p ON p.id = u.peralatan_id JOIN kategori_peralatan k ON k.id = p.kategori_id GROUP BY bulan, k.nama");
    $konf = getKonfig();
    $bulan = '';
    foreach ($kat as $r) {
        if (strcmp($r['bulan'], $bulan) > 0) $bulan = $r['bulan'];
    }
    $perKat = [];
    foreach ($kat as $r) {
        if ($r['bulan'] === $bulan) $perKat[] = ['nama' => $r['kategori'], 'kwh' => (float)$r['kwh']];
    }
    usort($perKat, function ($a, $b) {
        return $b['kwh'] <=> $a['kwh'];
    });
    $totalKwh = 0.0;
    foreach ($perKat as $r) $totalKwh += $r['kwh'];
    $per = 10;
    $page = min(max(1, parseIntJs(qparam('page')) ?: 1), max(1, (int)ceil(count($ref) / $per)));
    $bar = [];
    foreach ($perKat as $r) {
        $bar[] = [
            'label' => $r['nama'],
            'teks' => angka($r['kwh'], 1) . ' kWh · ' . angka($totalKwh ? ($r['kwh'] / $totalKwh) * 100 : 0, 1) . '%',
            'pct' => $totalKwh ? jsRound(($r['kwh'] / $totalKwh) * 1000) / 10 : 0,
        ];
    }
    render('admin', array_merge([
        'judul' => 'Admin', 'konf' => $konf, 'cfg' => $cfg, 'kategori' => $kategori,
        'ref' => array_slice($ref, ($page - 1) * $per, $per), 'refTotal' => count($ref), 'page' => $page, 'per' => $per,
        'agregat' => [
            'pengguna' => $pengguna, 'aktif' => $aktif, 'peralatan' => $peralatan, 'bulan' => $bulan, 'totalKwh' => $totalKwh,
            'biaya' => $totalKwh * $konf['tarif'], 'emisi' => $totalKwh * $konf['faktor'], 'rata' => $aktif ? $totalKwh / $aktif : 0, 'bar' => $bar,
        ],
        'errCfg' => [], 'vCfg' => null, 'scripts' => [],
    ], $extra), $status);
}

$router->get('/', function () {
    renderAdmin();
});

$router->post('/konfigurasi', function () {
    $v = ['tarif' => parseAngkaId(fparam('tarif')), 'faktor' => parseAngkaId(fparam('faktor')), 'sumber' => trim(fparam('sumber')), 'berlaku' => fparam('berlaku')];
    $e = [];
    if (!($v['tarif'] > 0 && $v['tarif'] < 1e7)) $e['tarif'] = 'Tarif harus lebih dari 0.';
    if (!($v['faktor'] > 0 && $v['faktor'] <= 10)) $e['faktor'] = 'Faktor emisi harus antara 0 dan 10.';
    if ($v['sumber'] === '' || mb_strlen($v['sumber']) > 255) $e['sumber'] = 'Sumber wajib diisi (maks. 255 karakter).';
    $tglOk = preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $v['berlaku'], $t) && checkdate((int)$t[2], (int)$t[3], (int)$t[1]);
    if (!$tglOk) $e['berlaku'] = 'Tanggal berlaku tidak valid.';
    if ($e) {
        renderAdmin(['errCfg' => $e, 'vCfg' => ['tarif' => fparam('tarif'), 'faktor' => fparam('faktor'), 'sumber' => $v['sumber'], 'berlaku' => $v['berlaku']]], 400);
    }
    eksekusi('INSERT INTO konfigurasi_emisi_tarif (faktor_emisi, tarif_per_kwh, sumber, berlaku_mulai) VALUES (?,?,?,?)',
        [number_format($v['faktor'], 4, '.', ''), number_format($v['tarif'], 2, '.', ''), $v['sumber'], $v['berlaku']]);
    Cache::clearAll();
    redirect('/admin');
});

// ---- Referensi peralatan ----
function bentukRef(): array
{
    return ['nama' => trim(fparam('nama')), 'kategori_id' => parseIntJs(fparam('kategori_id')), 'daya' => parseIntJs(fparam('daya'))];
}

function cekRef(array $v): array
{
    $e = [];
    if ($v['nama'] === '' || mb_strlen($v['nama']) > 100) $e['nama'] = 'Nama wajib diisi (maks. 100 karakter).';
    if (!($v['kategori_id'] !== null && $v['kategori_id'] > 0)) $e['kategori_id'] = 'Pilih kategori.';
    if (!antara($v['daya'], 1, 10000)) $e['daya'] = "Daya harus 1\u{2013}10.000 W.";
    if (!isset($e['kategori_id']) && !q('SELECT id FROM kategori_peralatan WHERE id = ?', [$v['kategori_id']])) $e['kategori_id'] = 'Kategori tidak ditemukan.';
    return $e;
}

function formRef($id, array $v, array $errors = [], int $status = 200): void
{
    renderAdmin(['modal' => 'ref', 'formRef' => ['id' => $id, 'v' => $v, 'errors' => $errors]], $status);
}

$router->get('/referensi/baru', function () {
    formRef(null, ['nama' => '', 'kategori_id' => '', 'daya' => '']);
});
$router->get('/referensi/:id/ubah', function (array $p) {
    $id = (int)$p['id'];
    $r = q('SELECT nama, kategori_id, daya_default_w FROM referensi_peralatan WHERE id = ?', [$id])[0] ?? null;
    if (!$r) redirect('/admin');
    formRef($id, ['nama' => $r['nama'], 'kategori_id' => $r['kategori_id'], 'daya' => $r['daya_default_w']]);
});
$router->post('/referensi', function () {
    $v = bentukRef();
    $e = cekRef($v);
    if ($e) formRef(null, $v, $e, 400);
    eksekusi('INSERT INTO referensi_peralatan (kategori_id, nama, daya_default_w) VALUES (?,?,?)', [$v['kategori_id'], $v['nama'], $v['daya']]);
    redirect('/admin');
});
$router->post('/referensi/:id', function (array $p) {
    $id = (int)$p['id'];
    $v = bentukRef();
    $e = cekRef($v);
    if ($e) formRef($id, $v, $e, 400);
    eksekusi('UPDATE referensi_peralatan SET kategori_id = ?, nama = ?, daya_default_w = ? WHERE id = ?', [$v['kategori_id'], $v['nama'], $v['daya'], $id]);
    redirect('/admin');
});
$router->get('/referensi/:id/hapus', function (array $p) {
    $r = q('SELECT id, nama FROM referensi_peralatan WHERE id = ?', [(int)$p['id']])[0] ?? null;
    if (!$r) redirect('/admin');
    renderAdmin(['modal' => 'hapus-ref', 'hapus' => $r]);
});
$router->post('/referensi/:id/hapus', function (array $p) {
    eksekusi('DELETE FROM referensi_peralatan WHERE id = ?', [(int)$p['id']]);
    redirect('/admin');
});

return $router;
