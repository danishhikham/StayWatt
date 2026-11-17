<?php
$router = new Router();

// Hash tak terpakai agar waktu respons sama saat email tidak ditemukan.
const DUMMY_HASH = '$2b$10$aPSutdgZSvPRIAggC5YKC.NnYfdsOOwNS1nkE9nljsVAfkkF/Bhn2';

function emailOk(string $e): bool
{
    return (bool)preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/u', $e);
}

function tampilAuth(string $mode, array $errors = [], array $v = [], int $status = 200): void
{
    render('auth', ['judul' => $mode === 'masuk' ? 'Masuk' : 'Daftar', 'mode' => $mode, 'errors' => $errors, 'v' => $v], $status);
}

function mulaiSesi(array $u): void
{
    $_SESSION = [];
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => (int)$u['id'], 'nama' => $u['nama'], 'email' => $u['email'], 'role' => $u['role']];
    redirect('/');
}

$router->get('/login', function () {
    if (currentUser()) redirect('/');
    tampilAuth('masuk');
});
$router->get('/daftar', function () {
    if (currentUser()) redirect('/');
    tampilAuth('daftar');
});

$router->post('/login', function () {
    $email = mb_strtolower(trim(fparam('email')));
    $password = fparam('password');
    $u = q('SELECT id, nama, email, password_hash, role FROM users WHERE email = ?', [$email])[0] ?? null;
    $ok = password_verify($password, $u ? $u['password_hash'] : DUMMY_HASH);
    if (!$u || !$ok) tampilAuth('masuk', ['umum' => 'Email atau kata sandi salah.'], ['email' => $email], 401);
    mulaiSesi($u);
});

$router->post('/daftar', function () {
    $nama = trim(fparam('nama'));
    $email = mb_strtolower(trim(fparam('email')));
    $password = fparam('password');
    $errors = [];
    if ($nama === '' || mb_strlen($nama) > 100) $errors['nama'] = 'Nama wajib diisi (maks. 100 karakter).';
    if (!emailOk($email) || mb_strlen($email) > 150) $errors['email'] = 'Format email tidak valid.';
    if (mb_strlen($password) < 8) $errors['password'] = 'Kata sandi minimal 8 karakter.';
    if ($errors) tampilAuth('daftar', $errors, ['nama' => $nama, 'email' => $email], 400);
    try {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        eksekusi("INSERT INTO users (nama, email, password_hash, role) VALUES (?,?,?,'pengguna')", [$nama, $email, $hash]);
        $id = (int)db()->lastInsertId();
    } catch (PDOException $e) {
        if ((int)($e->errorInfo[1] ?? 0) === 1062) tampilAuth('daftar', ['email' => 'Email sudah terdaftar.'], ['nama' => $nama, 'email' => $email], 400);
        throw $e;
    }
    mulaiSesi(['id' => $id, 'nama' => $nama, 'email' => $email, 'role' => 'pengguna']);
});

$router->post('/keluar', function () {
    $_SESSION = [];
    session_destroy();
    redirect('/login');
});

$router->post('/tema', function () {
    $baru = App::$tema === 'terang' ? 'gelap' : 'terang';
    setcookie('tema', $baru, ['expires' => time() + 365 * 24 * 3600, 'path' => '/', 'samesite' => 'Lax']);
    $balik = u('/');
    $ref = parse_url($_SERVER['HTTP_REFERER'] ?? '');
    if ($ref && isset($ref['host'])) {
        $host = $ref['host'] . (isset($ref['port']) ? ':' . $ref['port'] : '');
        if ($host === ($_SERVER['HTTP_HOST'] ?? '')) {
            $balik = '/' . ltrim(($ref['path'] ?? '/') . (isset($ref['query']) ? '?' . $ref['query'] : ''), '/');
        }
    }
    header('Location: ' . $balik, true, 302);
    exit;
});

return $router;
