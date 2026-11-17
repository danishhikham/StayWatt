<?php
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        // DB_* wins for existing installations; Railway's MYSQL* variables are fallbacks.
        $host = env('DB_HOST', env('MYSQLHOST', '127.0.0.1'));
        $port = (int)env('DB_PORT', env('MYSQLPORT', 3306));
        $name = env('DB_NAME', env('MYSQLDATABASE', 'staywatt'));
        $user = env('DB_USER', env('MYSQLUSER', 'root'));
        $pass = env('DB_PASSWORD', env('MYSQLPASSWORD', ''));
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

// Semua kueri memakai prepared statement.
function q(string $sql, array $params = []): array
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

function eksekusi(string $sql, array $params = []): int
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->rowCount();
}
