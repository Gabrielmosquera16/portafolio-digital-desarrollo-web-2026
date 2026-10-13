<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function baseUrl(string $path = ''): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
    $position = strpos($script, '/public/');
    $base = $position === false ? '' : substr($script, 0, $position + 7);
    $base = rtrim($base, '/');

    return $base . ($path === '' ? '/' : '/' . ltrim($path, '/'));
}

function redirectTo(string $path): never
{
    header('Location: ' . baseUrl($path));
    exit;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf(): bool
{
    $sent = $_POST['csrf_token'] ?? '';
    $saved = $_SESSION['csrf_token'] ?? '';

    return is_string($sent) && is_string($saved) && $sent !== '' && hash_equals($saved, $sent);
}

function isAdmin(): bool
{
    return isset($_SESSION['admin_id']) && is_int($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        redirectTo('admin/login.php');
    }
}

function isLocalRequest(): bool
{
    return in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
}

function setFlash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function takeFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($flash) ? $flash : null;
}
