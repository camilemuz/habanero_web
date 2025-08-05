<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['usuario'] ?? '');
    $pass = $_POST['contrasena'] ?? '';
    $csrf = $_POST['csrf'] ?? '';
    if (!verify_csrf($csrf)) {
        $error = 'CSRF token inválido.';
    } else {
        if (login($user, $pass)) {
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container section narrow">
  <h1>Acceso de administrador</h1>
  <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="post" class="card form">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($token) ?>">
    <label>Usuario
      <input type="text" name="usuario" required>
    </label>
    <label>Contraseña
      <input type="password" name="contrasena" required>
    </label>
    <button type="submit" class="btn">Ingresar</button>
  </form>
  <p><a href="index.php">← Volver al sitio</a></p>
</div>
</body>
</html>
