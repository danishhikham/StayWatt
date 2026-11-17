<?php
// kondisi: "nama~AC;porsi>=30;kurang=1"  (operator: >= <= ~ =; "kurang" = jam yang dikurangi)
function uraiKondisi($kond): array
{
    $syarat = [];
    $kurang = 1.0;
    foreach (explode(';', (string)$kond) as $t) {
        if (!preg_match('/^(\w+)\s*(>=|<=|~|=)\s*(.+)$/', trim($t), $m)) continue;
        if ($m[1] === 'kurang') { $kurang = (float)$m[3]; continue; }
        $syarat[] = ['k' => $m[1], 'op' => $m[2], 'v' => $m[3]];
    }
    return ['syarat' => $syarat, 'kurang' => $kurang];
}

function lolosSyarat(array $syarat, array $ctx): bool
{
    foreach ($syarat as $s) {
        $x = $ctx[$s['k']] ?? null;
        $v = $s['v'];
        if ($s['op'] === '~') {
            $kata = preg_split('/\s+/u', mb_strtolower((string)$x)) ?: [];
            $ok = in_array(mb_strtolower(trim($v)), $kata, true);
        } elseif ($s['op'] === '>=') {
            $ok = (float)$x >= (float)$v;
        } elseif ($s['op'] === '<=') {
            $ok = (float)$x <= (float)$v;
        } else {
            $ok = (string)$x === $v;
        }
        if (!$ok) return false;
    }
    return true;
}

function buatRekomendasi(?array $bulan, array $cfg): array
{
    if (!$bulan) return ['daftar' => [], 'total' => ['kwh' => 0, 'biaya' => 0, 'emisi' => 0, 'persen' => 0]];
    $baris = Cache::remember('aturan', function () {
        return q('SELECT kategori_id, kondisi, saran FROM rekomendasi_aturan ORDER BY id');
    });
    $aturan = [];
    foreach ($baris as $a) $aturan[] = $a + uraiKondisi($a['kondisi']);
    $daftar = [];
    foreach ($bulan['items'] as $it) {
        $ctx = ['nama' => $it['nama'], 'porsi' => $it['porsi'], 'jam' => $it['jamPerHari']];
        $a = null;
        foreach ($aturan as $x) {
            if ((int)$x['kategori_id'] === $it['kategoriId'] && lolosSyarat($x['syarat'], $ctx)) { $a = $x; break; }
        }
        if (!$a) continue;
        $kurang = min($a['kurang'], $it['jamPerHari']);
        $hemat = kwh(['dayaW' => $it['dayaW'], 'jamPerHari' => $kurang, 'hari' => $bulan['hari'], 'jumlah' => $it['jumlah']]);
        $peta = ['nama' => $it['nama'], 'porsi' => angka($it['porsi'], 1), 'jam' => jamFmt($it['jamPerHari']), 'kurang' => jamFmt($kurang)];
        $teks = preg_replace_callback('/\{(\w+)\}/', function ($m) use ($peta) {
            return $peta[$m[1]] ?? '';
        }, $a['saran']);
        $daftar[] = [
            'id' => $it['id'], 'nama' => $it['nama'], 'teks' => $teks, 'jamBaru' => $it['jamPerHari'] - $kurang,
            'kwh' => $hemat, 'biaya' => $hemat * $cfg['tarif'], 'emisi' => $hemat * $cfg['faktor'],
        ];
    }
    usort($daftar, function ($x, $y) {
        return $y['kwh'] <=> $x['kwh'];
    });
    $k = 0.0;
    foreach ($daftar as $d) $k += $d['kwh'];
    return ['daftar' => $daftar, 'total' => ['kwh' => $k, 'biaya' => $k * $cfg['tarif'], 'emisi' => $k * $cfg['faktor'], 'persen' => $bulan['total']['kwh'] ? ($k / $bulan['total']['kwh']) * 100 : 0]];
}
