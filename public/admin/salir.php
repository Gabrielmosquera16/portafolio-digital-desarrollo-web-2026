<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isAdmin() || !verifyCsrf()) {
    http_response_code(400);
    exit('Solicitud no válida.');
}
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $parameters = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $parameters['path'],
        'domain' => $parameters['domain'],
        'secure' => $parameters['secure'],
        'httponly' => $parameters['httponly'],
        'samesite' => 'Lax',
    ]);
}
session_destroy();
header('Location: ' . baseUrl('index.php'));
exit;
