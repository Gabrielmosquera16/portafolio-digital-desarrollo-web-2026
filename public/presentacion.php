<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
$profile = $repository->profile();
$interests = $repository->interests();
$pageTitle = 'Presentación general';
$activePage = 'presentacion';
require __DIR__ . '/partials/header.php';
?>
<main id="contenido" class="shell page-shell">
  <p class="eyebrow">01 · PRESENTACIÓN GENERAL</p>
  <div class="page-heading"><h1>Quién soy y qué espero aprender.</h1><p>Una presentación personal para acompañar el trabajo realizado en la asignatura.</p></div>
  <section class="profile-layout">
    <figure class="profile-photo"><img src="<?= e(baseUrl('assets/images/gabriel-mosquera.jpeg')) ?>" alt="Retrato de Gabriel Oriel Mosquera Magallón" width="864" height="1184"><figcaption>Gabriel Oriel Mosquera Magallón</figcaption></figure>
    <div class="profile-content">
      <section class="content-panel"><p class="eyebrow">PERFIL</p><h2><?= e($profile['nombre_completo'] ?? 'Gabriel Oriel Mosquera Magallón') ?></h2><p><?= e($profile['biografia'] ?? '') ?></p><p class="profile-detail"><strong>Formación:</strong> <?= e($profile['formacion'] ?? '') ?></p><p class="profile-detail"><strong>Estudios actuales:</strong> <?= e($profile['estudios_actuales'] ?? '') ?></p><p class="profile-detail"><strong>Experiencia:</strong> <?= e($profile['cargo'] ?? '') ?> · <?= e($profile['organizacion'] ?? '') ?></p></section>
      <section class="content-panel"><p class="eyebrow">HOBBIES Y PASATIEMPOS</p><div class="tag-list"><?php foreach ($interests as $interest): ?><span class="interest-tag"><?= e($interest['nombre']) ?></span><?php endforeach; ?></div></section>
      <section class="goal-panel"><p class="eyebrow">EXPECTATIVAS DEL CURSO</p><h2>Aprender con intención.</h2><p><?= e($profile['expectativas'] ?? '') ?></p></section>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
