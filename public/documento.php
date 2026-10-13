<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';

$fileId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$file = $fileId ? $repository->fileById((int) $fileId) : null;
if ($file === null || ($file['estado_entrada'] !== 'publicado' && !isAdmin())) {
    http_response_code(404);
    exit('Documento no encontrado.');
}

try {
    $path = $fileStorage->resolve((string) $file['ruta_guardada']);
} catch (RuntimeException) {
    http_response_code(404);
    exit('Documento no disponible.');
}

$name = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $file['nombre_original']) ?: 'documento.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $name . '"');
header('Content-Length: ' . (string) filesize($path));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($path);
exit;
