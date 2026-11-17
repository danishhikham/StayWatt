<?php
// Menjalankan server bawaan PHP agar dapat diakses dari Railway dan jaringan lokal.
require __DIR__ . '/../lib/env.php';
loadEnv(dirname(__DIR__) . '/.env');
$port = (int)env('PORT', 3000);
$root = dirname(__DIR__);
$host = '0.0.0.0';
echo "StayWatt berjalan di http://$host:$port\n";
passthru(escapeshellarg(PHP_BINARY) . " -S $host:$port -t " . escapeshellarg("$root/public") . ' ' . escapeshellarg("$root/public/index.php"));
