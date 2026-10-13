<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
$profile = $repository->profile();
$sections = $repository->rootSections();
$pageTitle = 'Portafolio Digital Estudiantil';
$activePage = 'inicio';
require __DIR__ . '/partials/header.php';
?>
<main id="contenido">
  <section class="hero shell">
    <div class="hero-copy">
      <p class="eyebrow">Universidad Tecnológica de Panamá · Facultad de Ingeniería de Sistemas Computacionales · Departamento de Programación de Computadoras</p>
      <p class="eyebrow">PORTAFOLIO DIGITAL ESTUDIANTIL</p>
      <p class="index-number">PORTAFOLIO <span>01 / 2026</span></p>
      <h1>Desarrollo Web,<br><em>aprendizaje en práctica.</em></h1>
      <p class="hero-lead">Un registro ordenado de mi recorrido por HTML5, CSS, PHP y MySQL, con atención a la organización y la seguridad de las aplicaciones web.</p>
      <div class="hero-meta"><span>Asignatura 1269</span><span>Sección 1S3221</span><span>II Semestre 2026</span><span>Facilitador: Giovani Antonio Sánchez Gómez</span></div>
      <a class="button button-primary" href="<?= e(baseUrl('presentacion.php')) ?>">Conocer al estudiante <span aria-hidden="true">→</span></a>
    </div>
    <div class="hero-portrait">
      <img src="<?= e(baseUrl('assets/images/gabriel-mosquera.jpeg')) ?>" alt="Retrato de Gabriel Oriel Mosquera Magallón" width="864" height="1184">
      <p class="portrait-caption"><span>01</span> Presentación general del estudiante</p>
    </div>
  </section>

  <section class="intro-band">
    <div class="shell intro-grid">
      <p class="eyebrow">ENFOQUE DEL CURSO</p>
      <p>Desarrollar aplicaciones web dinámicas y seguras, integrando tecnologías de interfaz, programación backend y bases de datos relacionales.</p>
    </div>
  </section>

  <section class="shell section-block" aria-labelledby="sections-title">
    <div class="section-heading">
      <div><p class="eyebrow">ÍNDICE DEL PORTAFOLIO</p><h2 id="sections-title">Un lugar para cada evidencia.</h2></div>
      <p>Las secciones pendientes se irán completando a medida que avance el semestre. No se agregan trabajos ni reflexiones que aún no se hayan realizado.</p>
    </div>
    <div class="section-list">
      <?php foreach ($sections as $index => $section): ?>
        <a class="section-row" href="<?= e(baseUrl('seccion.php?slug=' . rawurlencode($section['slug']))) ?>">
          <span class="section-index"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
          <span class="section-copy"><strong><?= e($section['nombre']) ?></strong><small><?= e($section['descripcion']) ?></small></span>
          <span class="section-count"><?= e((string) $section['cantidad']) ?> <?= (int) $section['cantidad'] === 1 ? 'elemento' : 'elementos' ?></span>
          <span class="arrow" aria-hidden="true">↗</span>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
