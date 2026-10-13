<?php
declare(strict_types=1);
$directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$directory = rtrim($directory, '/');
header('Location: ' . ($directory === '' ? '' : $directory) . '/public/');
exit;
