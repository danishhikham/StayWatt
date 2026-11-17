<?php
// Dimuat oleh server.php (web) dan oleh skrip CLI (seed.php, scripts/init-db.php).
define('ROOT', dirname(__DIR__));
require_once __DIR__ . '/env.php';
loadEnv(ROOT . '/.env');
date_default_timezone_set(env('TIMEZONE', 'Asia/Jakarta'));
mb_internal_encoding('UTF-8');
foreach (['format', 'hitung', 'util', 'db', 'cache', 'icons', 'data', 'rekomendasi', 'simulasi'] as $m) {
    require_once __DIR__ . "/$m.php";
}
