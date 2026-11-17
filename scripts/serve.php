<?php
// Menjalankan server bawaan PHP di 127.0.0.1:PORT (default 3000), setara `npm start` versi Node.
require __DIR__ . '/../lib/env.php';
loadEnv(dirname(__DIR__) . '/.env');
$port = (int)env('PORT', 3000);
$root = dirname(__DIR__);
echo "StayWatt berjalan di http://localhost:$port\n";
passthru(escapeshellarg(PHP_BINARY) . " -S 127.0.0.1:$port -t " . escapeshellarg("$root/public") . ' ' . escapeshellarg("$root/public/index.php"));
