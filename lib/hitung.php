<?php
// Satu-satunya tempat rumus. Dipakai form peralatan, dashboard, rekomendasi, dan simulasi.
function kwh(array $a): float
{
    return ($a['dayaW'] * $a['jamPerHari'] * $a['hari'] * $a['jumlah']) / 1000;
}

function biaya(float $k, float $tarif): float
{
    return $k * $tarif;
}

function emisi(float $k, float $faktor): float
{
    return $k * $faktor;
}

function estimasi(array $input, array $cfg): array
{
    $k = kwh($input);
    return ['kwh' => $k, 'biaya' => biaya($k, $cfg['tarif']), 'emisi' => emisi($k, $cfg['faktor'])];
}

function hariDalamBulan(string $periode): int
{
    [$y, $m] = array_map('intval', explode('-', $periode));
    return (int)date('t', mktime(0, 0, 0, $m, 1, $y));
}

function persenPerubahan($sekarang, $lalu): ?float
{
    return $lalu ? (($sekarang - $lalu) / $lalu) * 100 : null;
}
