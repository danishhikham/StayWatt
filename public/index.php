<?php
// Titik masuk web. Dengan server bawaan PHP (php -S), berkas statis di public/ disajikan langsung.
if (PHP_SAPI === 'cli-server') {
    $p = (string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($p !== '/' && $p !== '/index.php' && is_file(__DIR__ . $p)) return false;
}
require dirname(__DIR__) . '/server.php';
