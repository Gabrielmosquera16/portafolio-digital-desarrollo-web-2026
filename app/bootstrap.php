<?php
declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));
define('STORAGE_ROOT', APP_ROOT . DIRECTORY_SEPARATOR . 'storage');
date_default_timezone_set('America/Panama');

require_once APP_ROOT . '/app/Database.php';
require_once APP_ROOT . '/app/Security.php';
require_once APP_ROOT . '/app/FileStorage.php';
require_once APP_ROOT . '/app/PortfolioRepository.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('portafolio_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$configPath = APP_ROOT . '/config/database.php';
if (!is_file($configPath)) {
    http_response_code(503);
    exit('Falta la configuración local. Copia config/database.example.php como config/database.php y sigue los pasos de README.md.');
}

$dbConfig = require $configPath;
$pdo = Database::connect($dbConfig);
$repository = new PortfolioRepository($pdo);
$fileStorage = new FileStorage(STORAGE_ROOT);
