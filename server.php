<?php
// Pengganti server.js: sesi, tema, CSRF, routing, dan penanganan galat.
require __DIR__ . '/lib/boot.php';
require __DIR__ . '/lib/view.php';
require __DIR__ . '/lib/router.php';

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
header_remove('X-Powered-By');

// Lokasi aplikasi (mis. "/staywatt" bila dipasang di htdocs/staywatt pada XAMPP; kosong bila di root).
function deteksiBase(string $reqPath): string
{
    $e = env('BASE_URL');
    if ($e !== null) {
        $e = trim($e, '/');
        return $e === '' ? '' : '/' . $e;
    }
    if (PHP_SAPI === 'cli-server') return '';
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($base !== '' && strpos($reqPath, $base) !== 0 && substr($base, -7) === '/public') {
        $base = substr($base, 0, -7);
    }
    return $base;
}

$reqPath = rawurldecode((string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'));
App::$base = deteksiBase($reqPath);

$pub = __DIR__ . '/public';
$mtime = function ($f) { return is_file($f) ? filemtime($f) : 0; };
App::$asset = max($mtime("$pub/css/app.css"), $mtime("$pub/js/dashboard.js"), $mtime("$pub/js/simulasi.js"), $mtime("$pub/js/peralatan-form.js"), $mtime("$pub/js/stepper.js"));

// Sesi (cookie httpOnly, SameSite=Lax, 7 hari).
ini_set('session.use_strict_mode', '1');
ini_set('session.gc_maxlifetime', (string)(7 * 24 * 3600));
session_name('staywatt_sid');
session_set_cookie_params(['lifetime' => 7 * 24 * 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
session_start();

// Tema (preferensi pengguna, disimpan di cookie), sesi, dan CSRF.
App::$tema = (($_COOKIE['tema'] ?? '') === 'terang') ? 'terang' : 'gelap';
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
App::$csrf = $_SESSION['csrf'];

function masuk(): void
{
    if (empty($_SESSION['user'])) redirect('/login');
}

function hanyaAdmin(): void
{
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        render('galat', ['judul' => 'Ditolak', 'kode' => 403, 'pesan' => 'Halaman ini khusus admin.'], 403);
    }
}

function jalankan(string $path): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method === 'HEAD') $method = 'GET';

    if ($method === 'POST' && !hash_equals(App::$csrf, fparam('_csrf'))) {
        render('galat', ['judul' => 'Ditolak', 'kode' => 403, 'pesan' => 'Sesi formulir kedaluwarsa. Muat ulang halaman lalu coba lagi.'], 403);
    }

    // Landing page untuk pengunjung yang belum login (yang sudah login langsung ke dashboard).
    if ($path === '/' && $method === 'GET' && empty($_SESSION['user'])) {
        render('landing', ['judul' => 'Pantau listrik kosmu', 'scripts' => []]);
    }

    $auth = require __DIR__ . '/routes/auth.php';
    if ($m = $auth->match($method, $path)) { $m[0]($m[1]); return; }

    if ($path === '/admin' || strpos($path, '/admin/') === 0) {
        masuk();
        hanyaAdmin();
        $admin = require __DIR__ . '/routes/admin.php';
        $sub = substr($path, 6);
        if ($m = $admin->match($method, $sub === '' || $sub === false ? '/' : $sub)) { $m[0]($m[1]); return; }
    }

    masuk();
    $app = require __DIR__ . '/routes/app.php';
    if ($m = $app->match($method, $path)) { $m[0]($m[1]); return; }

    render('galat', ['judul' => 'Tidak ditemukan', 'kode' => 404, 'pesan' => 'Halaman yang kamu cari tidak ada.'], 404);
}

// Buang base dan garis miring penutup (Express tidak membedakan /peralatan dan /peralatan/).
$path = $reqPath;
if (App::$base !== '' && strpos($path, App::$base) === 0) $path = substr($path, strlen(App::$base));
if ($path === '' || $path[0] !== '/') $path = '/' . $path;
if ($path !== '/') $path = rtrim($path, '/');
if ($path === '' || $path === '/index.php') $path = '/';
if (env('APP_DEBUG') === '1') error_log("[StayWatt] metode=" . ($_SERVER['REQUEST_METHOD'] ?? '') . " uri=" . ($_SERVER['REQUEST_URI'] ?? '') . " base='" . App::$base . "' path='$path' user=" . (empty($_SESSION['user']) ? 'tamu' : 'login'));

try {
    jalankan($path);
} catch (Throwable $e) {
    error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    render('galat', ['judul' => 'Galat', 'kode' => 500, 'pesan' => 'Terjadi kesalahan pada server. Coba lagi sebentar.'], 500);
}