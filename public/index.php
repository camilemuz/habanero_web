<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../lib/posts.php';
$posts = posts_all();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($SITE_NAME) ?></title>
  <link rel="stylesheet" href="assets/styles1.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <div class="logo">Habanero Web</div>
    <nav>
      <a href="#inicio">Inicio</a>
      <a href="#sobre">Sobre nosotros</a>
      <a href="#portafolio">Portafolio</a>
      <a href="#servicios">Servicios</a>
      <a href="#contacto">Contacto</a>
      <a class="admin-link" href="login.php">Administrador</a>
    </nav>
  </div>
</header>

<main>
  <section id="inicio" class="hero">
    <div class="container">
      <h1>Construimos experiencias web profesionales</h1>
      <p>Listo para estar fuera de otro mundo?</p>
      <a href="#contacto" class="btn">Contáctanos</a>
    </div>
  </section>

  <section id="sobre" class="container section">
    <h2>Sobre nosotros</h2>
    <div class="cards-3">
      <div class="card">
        <h3>Confianza</h3>
        <p>Confia en nosotros</p>
      </div>
      <div class="card">
        <h3>Empatía</h3>
        <p>Ut wisi enim ad minim veniam, quis nostrud exerci.</p>
      </div>
      <div class="card">
        <h3>Responsabilidad</h3>
        <p>Laoreet dolore magna aliquam erat volutpat.</p>
      </div>
    </div>
  </section>

  <section id="portafolio" class="container section">
    <h2>Portafolio</h2>
    <p>Algunos de nuestros trabajos y publicaciones.</p>
    <div class="grid-posts">
      <?php foreach ($posts as $p): ?>
        <article class="post">
          <?php if (!empty($p['imagen'])): ?>
            <img src="uploads/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
          <?php endif; ?>
          <h3><?= htmlspecialchars($p['titulo']) ?></h3>
          <p><?= nl2br(htmlspecialchars(mb_strimwidth($p['contenido'], 0, 280, '...'))) ?></p>
          <small>Publicado: <?= htmlspecialchars($p['fecha']) ?></small>
        </article>
      <?php endforeach; ?>
      <?php if (empty($posts)): ?>
        <p>Aún no hay publicaciones.</p>
      <?php endif; ?>
    </div>
  </section>

  <section id="servicios" class="container section">
    <h2>Servicios</h2>
    <div class="cards-3">
      <div class="card"><h3>Desarrollo Web</h3><p>Sitios rápidos, seguros y escalables.</p></div>
      <div class="card"><h3>Branding</h3><p>Identidad visual y diseño UI/UX.</p></div>
      <div class="card"><h3>Soporte</h3><p>Mantenimiento y mejora continua.</p></div>
    </div>
  </section>

  <section id="contacto" class="container section">
    <h2>Contáctanos</h2>
    <form method="post" action="enviar.php" class="contact-form">
      <div class="row">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="tel" name="telefono" placeholder="Teléfono" required>
      </div>
      <textarea name="mensaje" placeholder="Breve descripción de su requerimiento" required></textarea>
      <button type="submit" class="btn">Enviar</button>
    </form>
  </section>
</main>

<footer class="site-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Habanero Web. Todos los derechos reservados.</p>
    <p>Dirección: Lorem ipsum dolor sit amet, consectetuer.</p>
  </div>
</footer>
</body>
</html>
