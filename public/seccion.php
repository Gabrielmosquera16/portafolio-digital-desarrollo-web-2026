<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
$slug = trim((string) ($_GET['slug'] ?? ''));
$section = $repository->sectionBySlug($slug);
if ($section === null) {
    http_response_code(404);
    $pageTitle = 'Sección no encontrada';
} else {
    $pageTitle = $section['nombre'];
}
$activePage = 'secciones';
$allSections = $repository->allSections();
$children = $section ? array_values(array_filter($allSections, static fn(array $row): bool => (int) ($row['parent_id'] ?? 0) === (int) $section['id'])) : [];
$entries = $section && $children === [] ? $repository->publishedEntries((int) $section['id']) : [];
require __DIR__ . '/partials/header.php';
?>
<main id="contenido" class="shell page-shell">
  <?php if ($section === null): ?>
    <section class="empty-state"><p class="eyebrow">404 · NO ENCONTRADO</p><h1>Esta sección no existe.</h1><a class="text-link" href="<?= e(baseUrl('index.php')) ?>">Volver al índice ↗</a></section>
  <?php else: ?>
    <p class="eyebrow">PORTAFOLIO · SECCIÓN</p>
    <div class="page-heading"><h1><?= e($section['nombre']) ?>.</h1><p><?= e($section['descripcion']) ?></p></div>
    <?php if ($children !== []): ?>
      <div class="directory-list">
        <?php foreach ($children as $index => $child): ?>
          <a class="section-row" href="<?= e(baseUrl('seccion.php?slug=' . rawurlencode($child['slug']))) ?>">
            <span class="section-index"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            <span class="section-copy"><strong><?= e($child['nombre']) ?></strong><small>Explorar evidencias y documentos de esta carpeta.</small></span>
            <span class="arrow" aria-hidden="true">↗</span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php elseif ($entries === []): ?>
      <div class="empty-state"><strong>Esta carpeta está preparada.</strong><p>Los materiales aparecerán aquí cuando se agreguen desde el panel administrativo. Cada actividad conserva su enunciado original y su solución cuando corresponda.</p></div>
    <?php else: ?>
      <div class="document-list">
        <?php foreach ($entries as $entry): ?>
          <article class="document-card">
            <div><p class="eyebrow">EVIDENCIA ACADÉMICA<?php if (!empty($entry['fecha_actividad'])): ?> · <?= e($entry['fecha_actividad']) ?><?php endif; ?></p><h2><?= e($entry['titulo']) ?></h2><p><?= e($entry['descripcion']) ?></p></div>
            <div class="document-links">
              <?php foreach ($repository->filesForEntry((int) $entry['id']) as $file): ?>
                <a class="text-link" href="<?= e(baseUrl('documento.php?id=' . (int) $file['id'])) ?>" target="_blank" rel="noopener"><?= e(ucfirst($file['tipo'])) ?> · <?= e($file['nombre_original']) ?> ↗</a>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
