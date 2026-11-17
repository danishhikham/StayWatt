<?php
// Parameter query (?a=b) sebagai string; null bila tidak ada / berupa array.
function qparam(string $k): ?string
{
    $v = $_GET[$k] ?? null;
    return is_string($v) ? $v : null;
}

// Parameter query sebagai angka; null bila bukan angka.
function qnum(string $k): ?float
{
    $v = qparam($k);
    return ($v !== null && is_numeric($v)) ? (float)$v : null;
}

// Field form (POST) sebagai string; '' bila tidak ada.
function fparam(string $k): string
{
    $v = $_POST[$k] ?? '';
    return is_string($v) ? $v : '';
}

// "1.444,70" -> 1444.7 ; "1444.70" -> 1444.7 ; tidak valid -> NAN
function parseAngkaId($s): float
{
    $s = trim((string)($s ?? ''));
    if (strpos($s, ',') !== false) $s = str_replace(',', '.', str_replace('.', '', $s));
    if ($s === '') return 0.0;
    return is_numeric($s) ? (float)$s : NAN;
}

function batas($v, float $min, float $max, float $def = 0): float
{
    if ($v === null || !is_numeric($v)) return $def;
    $n = (float)$v;
    return is_finite($n) ? min($max, max($min, $n)) : $def;
}

// parseInt(x, 10) milik JavaScript; null bila bukan angka.
function parseIntJs($s): ?int
{
    return preg_match('/^\s*[+-]?\d+/', (string)($s ?? ''), $m) ? (int)trim($m[0]) : null;
}

function antara($x, $min, $max): bool
{
    return $x !== null && $x >= $min && $x <= $max;
}
