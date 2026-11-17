<?php
// Menjalankan schema.sql ke database yang dipilih, termasuk Railway MySQL.
require __DIR__ . '/../lib/boot.php';

try {
    $sql = file_get_contents(ROOT . '/schema.sql');
    $host = env('DB_HOST', env('MYSQLHOST', '127.0.0.1'));
    $port = (int)env('DB_PORT', env('MYSQLPORT', 3306));
    $name = env('DB_NAME', env('MYSQLDATABASE', 'staywatt'));
    $user = env('DB_USER', env('MYSQLUSER', 'root'));
    $pass = env('DB_PASSWORD', env('MYSQLPASSWORD', ''));
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name),
        $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    foreach (explode(';', $sql) as $index => $stmt) {
        // The selected database already exists on hosted MySQL and may not allow CREATE DATABASE.
        if ($index < 2) continue;
        if (trim($stmt) !== '') $pdo->exec($stmt);
    }
    echo "Skema database siap.\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Gagal membuat skema: ' . $e->getMessage() . "\n");
    exit(1);
}
