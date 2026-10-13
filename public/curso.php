<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
$section = $repository->sectionBySlug('descripcion-del-curso');
$entries = $section ? $repository->publishedEntries((int) $section['id']) : [];
$pageTitle = 'Descripción del curso';
$activePage = 'curso';
require __DIR__ . '/partials/header.php';
?>
<main id="contenido" class="shell page-shell">
  <p class="eyebrow">02 · DESCRIPCIÓN DEL CURSO</p>
  <div class="page-heading"><h1>Desarrollo Web.</h1><p>Información académica y documentos del curso reunidos en una sola sección.</p></div>
  <section class="course-summary" aria-label="Datos de la asignatura">
    <div><span class="eyebrow">ASIGNATURA</span><strong>Desarrollo Web · 1269</strong></div>
    <div><span class="eyebrow">SECCIÓN / PERÍODO</span><strong>1S3221 · II Semestre 2026</strong></div>
    <div><span class="eyebrow">CRÉDITOS</span><strong>3 · 2 horas teoría / 3 laboratorio</strong></div>
    <div><span class="eyebrow">FACILITADOR</span><strong>Giovani Antonio Sánchez Gómez</strong></div>
  </section>
  <section class="content-panel course-objective"><p class="eyebrow">PROPÓSITO FORMATIVO</p><p>Desarrollar aplicaciones web dinámicas y seguras mediante el uso de tecnologías frontend y backend, aplicando programación orientada a objetos, diseño de interfaces, gestión de bases de datos y mecanismos de seguridad informática.</p></section>
  <section class="section-block compact-block">
    <div class="section-heading"><div><p class="eyebrow">DOCUMENTACIÓN</p><h2>Programa y planificación.</h2></div></div>
    <?php if ($entries === []): ?>
      <p class="empty-state">Aún no hay documentos publicados en esta sección.</p>
    <?php else: ?>
      <div class="document-list">
        <?php foreach ($entries as $entry): ?>
          <article class="document-card">
            <div><p class="eyebrow">DOCUMENTO DEL CURSO</p><h3><?= e($entry['titulo']) ?></h3><p><?= e($entry['descripcion']) ?></p></div>
            <div class="document-links">
              <?php foreach ($repository->filesForEntry((int) $entry['id']) as $file): ?>
                <a class="text-link" href="<?= e(baseUrl('documento.php?id=' . (int) $file['id'])) ?>" target="_blank" rel="noopener">Abrir PDF ↗</a>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
  <p class="muted-note">La planificación semestral del estudiante (formato FC-FISC-1-2-2017) se añadirá cuando esté disponible. No se muestran datos de contacto del facilitador.</p>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
