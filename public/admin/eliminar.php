<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf()) {
    http_response_code(400);
    exit('Solicitud no válida.');
}
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    http_response_code(400);
    exit('Identificador no válido.');
}
$files = $repository->filesForEntry((int) $id);
$pdo->beginTransaction();
try {
    $repository->deleteEntry((int) $id);
    $pdo->commit();
    foreach ($files as $file) {
        $fileStorage->delete((string) $file['ruta_guardada']);
    }
    setFlash('Entrada eliminada.');
} catch (Throwable) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    setFlash('No se pudo eliminar la entrada.', 'error');
}
redirectTo('admin/index.php');
