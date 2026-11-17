<?php
// Format query "id:jam,id:jam" -> [id => jam]
function simUrai($s): array
{
    $m = [];
    foreach (explode(',', (string)($s ?? '')) as $p) {
        $bagian = explode(':', $p);
        $id = parseIntJs($bagian[0]) ?? 0;
        $b = $bagian[1] ?? '';
        if ($id > 0 && $b !== '' && is_numeric($b)) {
            $j = (float)$b;
            if ($j >= 0 && $j <= 24) $m[$id] = $j;
        }
    }
    return $m;
}

// What-if: tidak menyentuh database.
function simJalankan(array $bulan, array $cfg, array $perubahan): array
{
    $kS = 0.0;
    $kB = 0.0;
    $baris = [];
    foreach ($bulan['items'] as $it) {
        $baru = $perubahan[$it['id']] ?? $it['jamPerHari'];
        $kS += $it['kwh'];
        $kB += kwh(['dayaW' => $it['dayaW'], 'jamPerHari' => $baru, 'hari' => $bulan['hari'], 'jumlah' => $it['jumlah']]);
        $baris[$it['id']] = ['asal' => $it['jamPerHari'], 'baru' => $baru];
    }
    $sebelum = ['kwh' => $kS, 'biaya' => $kS * $cfg['tarif'], 'emisi' => $kS * $cfg['faktor']];
    $sesudah = ['kwh' => $kB, 'biaya' => $kB * $cfg['tarif'], 'emisi' => $kB * $cfg['faktor']];
    return ['baris' => $baris, 'sebelum' => $sebelum, 'sesudah' => $sesudah, 'persen' => $kS ? (($kB - $kS) / $kS) * 100 : 0];
}

function simTanda($n, int $d): string
{
    $r = jsRound($n * (10 ** $d));
    return $r > 0 ? '+' : ($r < 0 ? "\u{2212}" : '');
}

function simTampil(array $h): array
{
    $dK = $h['sesudah']['kwh'] - $h['sebelum']['kwh'];
    $dB = $h['sesudah']['biaya'] - $h['sebelum']['biaya'];
    $dE = $h['sesudah']['emisi'] - $h['sebelum']['emisi'];
    $arah = jsRound($dK * 10) == 0 ? 0 : ($dK < 0 ? -1 : 1);
    $baris = [];
    foreach ($h['baris'] as $id => $b) {
        $baris[$id] = ['ke' => angka($b['baru'], 1), 'arah' => abs($b['baru'] - $b['asal']) < 1e-9 ? 0 : ($b['baru'] < $b['asal'] ? -1 : 1)];
    }
    return [
        'arah' => $arah, 'baris' => $baris,
        'badge' => ($arah < 0 ? "\u{2193} " : ($arah > 0 ? "\u{2191} " : '')) . angka(abs($h['persen']), 1) . '%',
        'kwh-sebelum' => angka($h['sebelum']['kwh'], 1), 'kwh-sesudah' => angka($h['sesudah']['kwh'], 1),
        'kwh-delta' => simTanda($dK, 1) . angka(abs($dK), 1) . ' kWh (' . simTanda($h['persen'], 1) . angka(abs($h['persen']), 1) . '%)',
        'biaya-sebelum' => rupiah($h['sebelum']['biaya']), 'biaya-sesudah' => rupiah($h['sesudah']['biaya']),
        'biaya-delta' => simTanda($dB, 0) . rupiah(abs($dB)),
        'emisi-sebelum' => angka($h['sebelum']['emisi'], 2) . ' kg', 'emisi-sesudah' => angka($h['sesudah']['emisi'], 2) . ' kg',
        'emisi-delta' => simTanda($dE, 2) . angka(abs($dE), 2) . " kg CO\u{2082}e",
    ];
}
