<?php
// Menjalankan schema.sql (CREATE DATABASE + semua tabel) ke MySQL/MariaDB (XAMPP).
require __DIR__ . '/../lib/boot.php';

try {
    $sql = file_get_contents(ROOT . '/schema.sql');
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;charset=utf8mb4', env('DB_HOST', '127.0.0.1'), (int)env('DB_PORT', 3306)),
        env('DB_USER', 'root'), env('DB_PASSWORD', ''), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    foreach (explode(';', $sql) as $stmt) {
        if (trim($stmt) !== '') $pdo->exec($stmt);
    }
    echo "Skema database siap.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Gagal membuat skema: ' . $e->getMessage() . "\n");
    exit(1);
}
