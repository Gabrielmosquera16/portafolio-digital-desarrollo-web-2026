<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
requireAdmin();
$idValue = $_GET['id'] ?? $_POST['id'] ?? null;
$id = $idValue === null ? null : filter_var($idValue, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($idValue !== null && $id === false) {
    http_response_code(400);
    exit('Identificador no válido.');
}
$entry = $id ? $repository->entryById((int) $id) : null;
if ($id && $entry === null) {
    http_response_code(404);
    exit('Entrada no encontrada.');
}
$sections = $repository->allSections();
$files = $entry ? $repository->filesForEntry((int) $entry['id']) : [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $error = 'La sesión del formulario expiró. Recarga la página e intenta otra vez.';
    } else {
        $sectionId = filter_var($_POST['seccion_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $title = trim((string) ($_POST['titulo'] ?? ''));
        $description = trim((string) ($_POST['descripcion'] ?? ''));
        $activityDate = trim((string) ($_POST['fecha_actividad'] ?? ''));
        $status = (string) ($_POST['estado'] ?? 'borrador');
        $validSectionIds = array_map(static fn(array $row): int => (int) $row['id'], $sections);
        $selectedSection = null;
        foreach ($sections as $sectionOption) {
            if ($sectionId && (int) $sectionOption['id'] === (int) $sectionId) {
                $selectedSection = $sectionOption;
                break;
            }
        }
        $dateObject = $activityDate === '' ? null : DateTime::createFromFormat('!Y-m-d', $activityDate);
        $dateErrors = DateTime::getLastErrors();
        $invalidDate = $activityDate !== '' && (
            $dateObject === false
            || $dateObject->format('Y-m-d') !== $activityDate
            || (is_array($dateErrors) && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
        );

        if (!$sectionId || !in_array((int) $sectionId, $validSectionIds, true) || $selectedSection === null) {
            $error = 'Selecciona una sección válida.';
        } elseif ($title === '' || mb_strlen($title, 'UTF-8') > 150) {
            $error = 'El título es obligatorio y no puede superar 150 caracteres.';
        } elseif ($description === '' || mb_strlen($description, 'UTF-8') > 4000) {
            $error = 'La descripción es obligatoria y no puede superar 4000 caracteres.';
        } elseif (!in_array($status, ['borrador', 'publicado'], true)) {
            $error = 'Selecciona un estado permitido.';
        } elseif ($invalidDate) {
            $error = 'La fecha debe tener el formato válido AAAA-MM-DD.';
        } else {
            $savedPaths = [];
            try {
                $pdo->beginTransaction();
                $data = [
                    'seccion_id' => (int) $sectionId,
                    'titulo' => $title,
                    'descripcion' => $description,
                    'fecha_actividad' => $activityDate === '' ? null : $activityDate,
                    'estado' => $status,
                ];
                $entryId = $id ? (int) $id : $repository->createEntry($data);
                if ($id) {
                    $repository->updateEntry($entryId, $data);
                }
                foreach (['enunciado' => 'enunciado', 'solucion' => 'solucion', 'material' => 'material'] as $input => $kind) {
                    if (($_FILES[$input]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    $saved = $fileStorage->savePdf($_FILES[$input], (string) $selectedSection['slug']);
                    $savedPaths[] = $saved['stored_path'];
                    $repository->addFile($entryId, $kind, $saved);
                }
                $pdo->commit();
                setFlash($id ? 'Entrada actualizada.' : 'Entrada creada.');
                redirectTo('admin/index.php');
            } catch (Throwable $exception) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                foreach ($savedPaths as $savedPath) {
                    $fileStorage->delete($savedPath);
                }
                $error = $exception instanceof InvalidArgumentException
                    ? $exception->getMessage()
                    : 'No se pudo guardar la entrada. Comprueba la conexión con MySQL y vuelve a intentarlo.';
            }
        }
        $formValues = ['seccion_id' => $sectionId ?: '', 'titulo' => $title, 'descripcion' => $description, 'fecha_actividad' => $activityDate, 'estado' => $status];
        $entry = array_merge($entry ?? [], $formValues);
    }
}
$pageTitle = $id ? 'Editar entrada' : 'Nueva entrada';
$activePage = '';
require __DIR__ . '/../partials/header.php';
?>
<main id="contenido" class="shell page-shell narrow-shell">
  <p class="eyebrow">PANEL PRIVADO · <?= $id ? 'EDITAR' : 'NUEVA' ?> EVIDENCIA</p>
  <div class="page-heading"><h1><?= $id ? 'Editar entrada.' : 'Agregar al portafolio.' ?></h1><p>Para actividades, conserva el enunciado original y la solución en PDF. Los archivos se guardan en la carpeta privada de su sección.</p></div>
  <?php if ($error !== ''): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form class="editor-form" method="post" enctype="multipart/form-data" action="<?= e(baseUrl('admin/guardar.php' . ($id ? '?id=' . (int) $id : ''))) ?>">
    <?= csrfField() ?>
    <?php if ($id): ?><input type="hidden" name="id" value="<?= (int) $id ?>"><?php endif; ?>
    <label>Carpeta del portafolio<select name="seccion_id" required><option value="">Seleccionar…</option><?php foreach ($sections as $sectionOption): ?><option value="<?= (int) $sectionOption['id'] ?>" <?= isset($entry['seccion_id']) && (int) $entry['seccion_id'] === (int) $sectionOption['id'] ? 'selected' : '' ?>><?= $sectionOption['parent_id'] !== null ? '↳ ' : '' ?><?= e($sectionOption['nombre']) ?></option><?php endforeach; ?></select></label>
    <label>Título<input name="titulo" maxlength="150" required value="<?= e($entry['titulo'] ?? '') ?>"></label>
    <label>Descripción<textarea name="descripcion" rows="5" maxlength="4000" required><?= e($entry['descripcion'] ?? '') ?></textarea></label>
    <div class="form-columns"><label>Fecha de la actividad<input type="date" name="fecha_actividad" value="<?= e($entry['fecha_actividad'] ?? '') ?>"></label><label>Visibilidad<select name="estado"><option value="borrador" <?= ($entry['estado'] ?? 'borrador') === 'borrador' ? 'selected' : '' ?>>Borrador</option><option value="publicado" <?= ($entry['estado'] ?? '') === 'publicado' ? 'selected' : '' ?>>Publicado</option></select></label></div>
    <label>Enunciado original (PDF, máximo 10 MB)<input type="file" name="enunciado" accept="application/pdf,.pdf"></label>
    <label>Solución (PDF, máximo 10 MB)<input type="file" name="solucion" accept="application/pdf,.pdf"></label>
    <label>Material de referencia (PDF, máximo 10 MB)<input type="file" name="material" accept="application/pdf,.pdf"></label>
    <?php if ($files !== []): ?><div class="current-files"><strong>Archivos ya asociados</strong><?php foreach ($files as $file): ?><a class="text-link" href="<?= e(baseUrl('documento.php?id=' . (int) $file['id'])) ?>" target="_blank" rel="noopener"><?= e(ucfirst($file['tipo'])) ?> · <?= e($file['nombre_original']) ?> ↗</a><?php endforeach; ?></div><?php endif; ?>
    <div class="form-actions"><button class="button button-primary" type="submit">Guardar entrada</button><a class="button button-secondary" href="<?= e(baseUrl('admin/index.php')) ?>">Cancelar</a></div>
  </form>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
