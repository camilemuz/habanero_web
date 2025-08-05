<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/posts.php';
require_login();

$alert = '';
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf'] ?? '')) {
        $alert = 'CSRF inválido.';
    } else {
        if ($action === 'create' || $action === 'update') {
            $titulo = trim($_POST['titulo'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            if ($titulo === '' || $contenido === '') {
                $alert = 'El título y el contenido son obligatorios.';
            } else {
                // Imagen (opcional)
                $newImageName = null;
                if (!empty($_FILES['imagen']['name'])) {
                    $file = $_FILES['imagen'];
                    if ($file['error'] === UPLOAD_ERR_OK) {
                        $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $file['tmp_name']);
                        finfo_close($finfo);
                        if (!in_array($mime, $allowed, true)) {
                            $alert = 'Formato de imagen no permitido.';
                        } elseif ($file['size'] > 5*1024*1024) {
                            $alert = 'La imagen supera 5MB.';
                        } else {
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $newImageName = bin2hex(random_bytes(8)) . '.' . strtolower($ext);
                            move_uploaded_file($file['tmp_name'], __DIR__ . '/uploads/' . $newImageName);
                        }
                    } else {
                        $alert = 'Error al subir imagen.';
                    }
                }
                if ($alert === '') {
                    if ($action === 'create') {
                        post_create($titulo, $contenido, $newImageName);
                        $alert = 'Post creado.';
                    } else {
                        $id = (int)($_POST['id'] ?? 0);
                        if ($id > 0) {
                            post_update($id, $titulo, $contenido, $newImageName);
                            $alert = 'Post actualizado.';
                        }
                    }
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                post_delete($id);
                $alert = 'Post eliminado.';
            }
        }
    }
}

// Para edición
$editPost = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editPost = post_find((int)$_GET['id']);
}

$token = csrf_token();
$posts = posts_all();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel de administración</title>
  <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <div class="logo">Admin · Habanero</div>
    <nav>
      <a href="index.php">Ver sitio</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
  </div>
</header>

<main class="container section">
  <h1>Gestión de publicaciones</h1>
  <?php if ($alert): ?><div class="alert"><?php echo htmlspecialchars($alert); ?></div><?php endif; ?>

  <div class="grid-2">
    <section class="card">
      <h2><?php echo $editPost ? 'Editar post' : 'Nuevo post'; ?></h2>
      <form method="post" enctype="multipart/form-data" class="form">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
        <input type="hidden" name="action" value="<?php echo $editPost ? 'update' : 'create'; ?>">
        <?php if ($editPost): ?>
          <input type="hidden" name="id" value="<?php echo (int)$editPost['id']; ?>">
        <?php endif; ?>
        <label>Título
          <input type="text" name="titulo" required value="<?php echo htmlspecialchars($editPost['titulo'] ?? ''); ?>">
        </label>
        <label>Contenido
          <textarea name="contenido" rows="6" required><?php echo htmlspecialchars($editPost['contenido'] ?? ''); ?></textarea>
        </label>
        <label>Imagen (JPG/PNG/WEBP/GIF, máx 5MB)
          <input type="file" name="imagen" <?php echo $editPost ? '' : 'required'; ?>>
        </label>
        <button type="submit" class="btn"><?php echo $editPost ? 'Guardar cambios' : 'Crear post'; ?></button>
        <?php if ($editPost): ?>
          <a class="btn ghost" href="admin.php">Cancelar</a>
        <?php endif; ?>
      </form>
    </section>

    <section>
      <h2>Posts existentes</h2>
      <div class="admin-posts">
        <?php foreach ($posts as $p): ?>
          <div class="card post-admin">
            <div class="thumb">
              <?php if (!empty($p['imagen'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($p['imagen']); ?>" alt="">
              <?php endif; ?>
            </div>
            <div class="meta">
              <h3><?php echo htmlspecialchars($p['titulo']); ?></h3>
              <small><?php echo htmlspecialchars($p['fecha']); ?></small>
            </div>
            <div class="actions">
              <a class="btn small" href="admin.php?action=edit&id=<?php echo (int)$p['id']; ?>">Editar</a>
              <form method="post" onsubmit="return confirm('¿Eliminar este post?');">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                <button type="submit" class="btn small danger">Eliminar</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?>
          <p>No hay posts aún.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</main>
</body>
</html>
