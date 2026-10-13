<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
requireAdmin();
$entries = $repository->allEntries();
$pageTitle = 'Administrar el portafolio';
$activePage = '';
require __DIR__ . '/../partials/header.php';
?>
<main id="contenido" class="shell page-shell">
  <div class="admin-heading"><div><p class="eyebrow">PANEL PRIVADO · <?= e($_SESSION['admin_name'] ?? 'Administrador') ?></p><h1>Entradas del portafolio.</h1></div><a class="button button-primary" href="<?= e(baseUrl('admin/guardar.php')) ?>">+ Nueva entrada</a></div>
  <div class="admin-toolbar"><p>Los borradores solo son visibles al iniciar sesión. Publica una entrada cuando esté lista para el profesor.</p><form method="post" action="<?= e(baseUrl('admin/salir.php')) ?>"><?= csrfField() ?><button class="button button-secondary" type="submit">Cerrar sesión</button></form></div>
  <?php if ($entries === []): ?>
    <div class="empty-state"><strong>El portafolio está listo para recibir evidencias.</strong><p>Agrega el enunciado original y la solución de cada actividad cuando corresponda.</p></div>
  <?php else: ?>
    <div class="table-wrap"><table class="admin-table"><thead><tr><th>Entrada</th><th>Sección</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
      <?php foreach ($entries as $entry): ?>
        <tr>
          <td><strong><?= e($entry['titulo']) ?></strong><small><?= e($entry['creado_en']) ?></small></td>
          <td><?= e($entry['seccion']) ?></td>
          <td><span class="status status-<?= e($entry['estado']) ?>"><?= e(ucfirst($entry['estado'])) ?></span></td>
          <td class="table-actions"><a class="text-link" href="<?= e(baseUrl('admin/guardar.php?id=' . (int) $entry['id'])) ?>">Editar</a>
            <form method="post" action="<?= e(baseUrl('admin/eliminar.php')) ?>" onsubmit="return confirm('¿Eliminar esta entrada y sus archivos adjuntos?');">
              <?= csrfField() ?><input type="hidden" name="id" value="<?= (int) $entry['id'] ?>"><button class="text-button danger-text" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody></table></div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
