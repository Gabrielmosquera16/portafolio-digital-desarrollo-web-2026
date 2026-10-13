<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
$pageTitle = 'Configurar administrador';
$activePage = '';
$error = '';

if (!isLocalRequest()) {
    http_response_code(403);
    exit('La configuración inicial solo se permite desde localhost.');
}
if ($repository->adminCount() > 0) {
    redirectTo('admin/login.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['usuario'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['confirmar_password'] ?? '');

    if (!verifyCsrf()) {
        $error = 'La sesión del formulario expiró. Recarga la página e intenta otra vez.';
    } elseif (!preg_match('/^[A-Za-z0-9._-]{3,32}$/', $username)) {
        $error = 'El usuario debe tener entre 3 y 32 caracteres: letras, números, punto, guion o guion bajo.';
    } elseif (strlen($password) < 12) {
        $error = 'La contraseña debe tener al menos 12 caracteres.';
    } elseif (!hash_equals($password, $confirmation)) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            $repository->createAdmin($username, password_hash($password, PASSWORD_DEFAULT));
            setFlash('Administrador creado. Ya puedes iniciar sesión.');
            redirectTo('admin/login.php');
        } catch (PDOException) {
            $error = 'No se pudo crear el administrador. Verifica que el nombre de usuario no esté en uso.';
        }
    }
}
require __DIR__ . '/../partials/header.php';
?>
<main id="contenido" class="shell page-shell narrow-shell">
  <p class="eyebrow">CONFIGURACIÓN LOCAL · PRIMER USO</p>
  <div class="page-heading"><h1>Crear acceso administrativo.</h1><p>Elige un usuario y una contraseña que no uses en otros servicios. Esta pantalla se cierra automáticamente después del primer alta.</p></div>
  <?php if ($error !== ''): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
  <form class="editor-form" method="post" action="<?= e(baseUrl('admin/setup.php')) ?>">
    <?= csrfField() ?>
    <label>Usuario<input name="usuario" minlength="3" maxlength="32" autocomplete="username" required></label>
    <label>Contraseña (mínimo 12 caracteres)<input type="password" name="password" minlength="12" autocomplete="new-password" required></label>
    <label>Confirmar contraseña<input type="password" name="confirmar_password" minlength="12" autocomplete="new-password" required></label>
    <button class="button button-primary" type="submit">Crear administrador</button>
  </form>
  <p class="muted-note">No hay credenciales iniciales en el código ni en el repositorio.</p>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
