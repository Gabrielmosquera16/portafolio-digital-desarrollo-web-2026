<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
$pageTitle = 'Acceso administrativo';
$activePage = '';
$error = '';
if (isAdmin()) {
    redirectTo('admin/index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['usuario'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if (!verifyCsrf()) {
        $error = 'La sesión del formulario expiró. Intenta nuevamente.';
    } else {
        $admin = $repository->adminByUsername($username);
        if ($admin !== null && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int) $admin['id'];
            $_SESSION['admin_name'] = $admin['usuario'];
            setFlash('Sesión iniciada.');
            redirectTo('admin/index.php');
        }
        $error = 'Usuario o contraseña no válidos.';
    }
}
require __DIR__ . '/../partials/header.php';
?>
<main id="contenido" class="shell page-shell narrow-shell">
  <p class="eyebrow">ÁREA DE ADMINISTRACIÓN</p>
  <div class="page-heading"><h1>Gestionar el portafolio.</h1><p>Este acceso permite organizar las evidencias que se mostrarán en las secciones públicas.</p></div>
  <?php if ($error !== ''): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form class="editor-form" method="post" action="<?= e(baseUrl('admin/login.php')) ?>">
    <?= csrfField() ?>
    <label>Usuario<input name="usuario" autocomplete="username" required></label>
    <label>Contraseña<input type="password" name="password" autocomplete="current-password" required></label>
    <button class="button button-primary" type="submit">Iniciar sesión</button>
  </form>
  <?php if ($repository->adminCount() === 0 && isLocalRequest()): ?><p class="muted-note"><a class="text-link" href="<?= e(baseUrl('admin/setup.php')) ?>">Primera vez: configurar administrador ↗</a></p><?php endif; ?>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
