<?php
// Memuat file .env sederhana (KEY=VALUE). Nilai yang sudah ada di environment tidak ditimpa.
function loadEnv(string $file): void
{
    if (!is_file($file)) return;
    foreach (file($file, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        $p = strpos($line, '=');
        if ($p === false) continue;
        $k = trim(substr($line, 0, $p));
        $v = trim(substr($line, $p + 1));
        if (strlen($v) >= 2 && ($v[0] === '"' || $v[0] === "'") && substr($v, -1) === $v[0]) $v = substr($v, 1, -1);
        if ($k !== '' && getenv($k) === false) { putenv("$k=$v"); $_ENV[$k] = $v; }
    }
}

function env(string $k, $def = null)
{
    $v = getenv($k);
    return ($v === false || $v === '') ? $def : $v;
}
