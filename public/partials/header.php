<?php
$pageTitle = $pageTitle ?? 'Portafolio Digital Estudiantil';
$activePage = $activePage ?? '';
$flashMessage = takeFlash();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <title><?= e($pageTitle) ?> · Gabriel Mosquera</title>
  <meta name="description" content="Portafolio académico de Desarrollo Web de Gabriel Oriel Mosquera Magallón.">
  <link rel="stylesheet" href="<?= e(baseUrl('assets/css/estilos.css')) ?>">
</head>
<body>
<a class="skip-link" href="#contenido">Saltar al contenido</a>
<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="<?= e(baseUrl('index.php')) ?>" aria-label="Inicio del portafolio">
      <span class="brand-mark" aria-hidden="true">GM</span>
      <span class="brand-copy"><strong>Portafolio</strong><small>Desarrollo Web · 2026</small></span>
    </a>
    <nav class="main-nav" aria-label="Navegación principal">
      <a class="<?= $activePage === 'inicio' ? 'is-active' : '' ?>" href="<?= e(baseUrl('index.php')) ?>">Inicio</a>
      <a class="<?= $activePage === 'presentacion' ? 'is-active' : '' ?>" href="<?= e(baseUrl('presentacion.php')) ?>">Presentación</a>
      <a class="<?= $activePage === 'curso' ? 'is-active' : '' ?>" href="<?= e(baseUrl('curso.php')) ?>">Descripción del curso</a>
      <a class="<?= $activePage === 'secciones' ? 'is-active' : '' ?>" href="<?= e(baseUrl('seccion.php?slug=actividades-asignaciones')) ?>">Actividades</a>
      <a class="admin-link" href="<?= e(baseUrl('admin/login.php')) ?>">Administrar</a>
    </nav>
  </div>
</header>
<?php if ($flashMessage !== null): ?>
  <div class="flash flash-<?= e($flashMessage['type']) ?>" role="status"><?= e($flashMessage['message']) ?></div>
<?php endif; ?>
