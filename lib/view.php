<?php
// Keadaan aplikasi untuk satu permintaan.
class App
{
    public static $base = '';
    public static $tema = 'gelap';
    public static $csrf = '';
    public static $asset = 0;
}

// Tumpukan data view: partial mewarisi data induknya, ditimpa oleh data yang diberikan (seperti include() di EJS).
class View
{
    public static $stack = [];
}

function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// URL internal (mendukung aplikasi yang dipasang di subfolder, mis. /staywatt).
function u(string $path = '/'): string
{
    return App::$base . $path;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function renderFile(string $__file, array $__data): string
{
    extract($__data, EXTR_SKIP);
    ob_start();
    try {
        include $__file;
    } catch (Throwable $__e) {
        ob_end_clean();
        throw $__e;
    }
    return (string)ob_get_clean();
}

function partial(string $name, array $vars = []): void
{
    $parent = View::$stack ? View::$stack[count(View::$stack) - 1] : [];
    $data = array_merge($parent, $vars);
    View::$stack[] = $data;
    try {
        echo renderFile(ROOT . '/views/partials/' . $name . '.php', $data);
    } finally {
        array_pop(View::$stack);
    }
}

function render(string $view, array $data = [], int $status = 200): void
{
    $data = array_merge(['tema' => App::$tema, 'user' => currentUser(), 'csrf' => App::$csrf, 'asset' => App::$asset], $data);
    View::$stack = [$data];
    $html = renderFile(ROOT . '/views/' . $view . '.php', $data);
    View::$stack = [];
    http_response_code($status);
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    exit;
}

function redirect(string $path): void
{
    header('Location: ' . u($path), true, 302);
    exit;
}

function jsonOut($data): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT);
    exit;
}
