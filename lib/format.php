<?php
const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const BULAN_PENDEK = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

// Math.round milik JavaScript (pembulatan setengah ke atas).
function jsRound($x): float
{
    return floor((float)$x + 0.5);
}

function bulatkan($n, int $d = 0): float
{
    $n = (float)$n;
    $f = 10 ** $d;
    return floor($n * $f + ($n >= 0 ? 1e-9 : -1e-9) + 0.5) / $f;
}

// Format angka Indonesia: titik ribuan, koma desimal.
function angka($n, int $d = 0): string
{
    $x = (float)$n;
    if (is_nan($x)) $x = 0.0;
    $v = bulatkan($x, $d);
    $neg = $v < 0 && abs($v) > 0;
    return ($neg ? '-' : '') . number_format(abs($v), $d, ',', '.');
}

function rupiah($n): string
{
    return (bulatkan($n) < 0 ? '-' : '') . 'Rp' . angka(abs((float)$n), 0);
}

function jamFmt($n): string
{
    $r = bulatkan($n, 1);
    return angka($n, $r == floor($r) ? 0 : 1);
}

function persen($n, int $d = 1): string
{
    return angka($n, $d) . '%';
}

function bertanda($n, int $d = 1): string
{
    return (bulatkan($n, $d) > 0 ? '+' : '') . angka($n, $d);
}

function namaBulan(string $p): string
{
    return BULAN[(int)explode('-', $p)[1] - 1];
}

function labelPeriode(string $p): string
{
    return namaBulan($p) . ' ' . explode('-', $p)[0];
}

function bulanPendek(string $p): string
{
    return BULAN_PENDEK[(int)explode('-', $p)[1] - 1];
}

// Angka -> teks seperti String(number) di JavaScript (tanpa notasi eksponen).
function jsNum($x): string
{
    if ($x === null || $x === '') return '';
    if (!is_numeric($x)) return (string)$x;
    $f = (float)$x;
    if ($f == floor($f) && abs($f) < 1e15) return (string)(int)$f;
    return rtrim(rtrim(number_format($f, 10, '.', ''), '0'), '.');
}
